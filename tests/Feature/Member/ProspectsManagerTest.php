<?php

use App\Livewire\Member\ProspectsManager;
use App\Models\Prospect;
use App\Models\Timeline;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

test('prospects page only lists prospects owned by the current member', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    Prospect::create(['user_id' => $user->id, 'first_name' => 'Mine', 'last_name' => 'Lead', 'email' => 'mine@example.test']);
    Prospect::create(['user_id' => $other->id, 'first_name' => 'Theirs', 'last_name' => 'Lead', 'email' => 'theirs@example.test']);

    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->assertSee('Mine Lead')
        ->assertDontSee('Theirs Lead');
});

test('search filters the prospect list', function () {
    $user = User::factory()->create();

    Prospect::create(['user_id' => $user->id, 'first_name' => 'Alpha', 'last_name' => 'Smith', 'email' => 'alpha@example.test']);
    Prospect::create(['user_id' => $user->id, 'first_name' => 'Beta', 'last_name' => 'Jones', 'email' => 'beta@example.test']);

    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->set('search', 'Alpha')
        ->assertSee('Alpha Smith')
        ->assertDontSee('Beta Jones');
});

test('a prospect can be created through the modal form', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->call('openCreate')
        ->set('first_name', 'New')
        ->set('last_name', 'Lead')
        ->set('email', 'new.lead@example.test')
        ->set('source', 'Referral')
        ->set('hotness', 'Hot')
        ->call('save')
        ->assertSet('showFormModal', false);

    $this->assertDatabaseHas('prospects', [
        'user_id' => $user->id,
        'first_name' => 'New',
        'last_name' => 'Lead',
        'email' => 'new.lead@example.test',
        'hotness' => 'Hot',
    ]);
});

test('creating a prospect requires a name and a way to contact them', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->call('openCreate')
        ->set('email', 'noname@example.test')
        ->call('save')
        ->assertHasErrors('first_name');

    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->call('openCreate')
        ->set('first_name', 'No')
        ->set('last_name', 'Contact')
        ->call('save')
        ->assertHasErrors('email');
});

test('duplicate email is only blocked within the same member, not across members', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    Prospect::create(['user_id' => $other->id, 'first_name' => 'Shared', 'last_name' => 'Email', 'email' => 'shared@example.test']);

    // Different member can use the same email without conflict.
    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->call('openCreate')
        ->set('first_name', 'My')
        ->set('last_name', 'Own')
        ->set('email', 'shared@example.test')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('prospects', ['user_id' => $user->id, 'email' => 'shared@example.test']);

    // The same member cannot add it twice.
    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->call('openCreate')
        ->set('first_name', 'Dup')
        ->set('last_name', 'Licate')
        ->set('email', 'shared@example.test')
        ->call('save')
        ->assertHasErrors('email');
});

test('a prospect can be edited and deleted, scoped to its owner', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    $prospect = Prospect::create(['user_id' => $user->id, 'first_name' => 'Edit', 'last_name' => 'Me', 'email' => 'edit@example.test']);
    $othersProspect = Prospect::create(['user_id' => $other->id, 'first_name' => 'Not', 'last_name' => 'Mine', 'email' => 'notmine@example.test']);

    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->call('openEdit', $prospect->id)
        ->assertSet('first_name', 'Edit')
        ->set('first_name', 'Edited')
        ->call('save');

    expect($prospect->fresh()->first_name)->toBe('Edited');

    // Cannot open or edit another member's prospect.
    expect(fn () => Livewire::actingAs($user)->test(ProspectsManager::class)->call('openEdit', $othersProspect->id))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->call('confirmDelete', $prospect->id)
        ->call('delete');

    $this->assertDatabaseMissing('prospects', ['id' => $prospect->id]);

    // Deleting another member's prospect id is a silent no-op (scoped query matches nothing).
    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->call('confirmDelete', $othersProspect->id)
        ->call('delete');

    $this->assertDatabaseHas('prospects', ['id' => $othersProspect->id]);
});

test('csv import creates valid rows and reports skipped ones', function () {
    $user = User::factory()->create();
    Prospect::create(['user_id' => $user->id, 'first_name' => 'Existing', 'last_name' => 'One', 'email' => 'existing@example.test']);

    $csv = "first_name,last_name,email,phone,source,hotness,notes\n"
        ."Good,Row,good.row@example.test,,Referral,Hot,First contact\n"
        .",,,,Facebook,Warm,No name or contact\n"
        ."Existing,One,existing@example.test,,Referral,Hot,Already exists\n"
        ."Bad,Email,not-an-email,,Facebook,Cold,Invalid email\n";

    $file = UploadedFile::fake()->createWithContent('prospects.csv', $csv);

    $component = Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->call('openImport')
        ->set('importFile', $file)
        ->call('import');

    $component->assertSet('importSummary.imported', 1);
    $component->assertSet('importSummary.skipped', 3);

    $this->assertDatabaseHas('prospects', [
        'user_id' => $user->id,
        'first_name' => 'Good',
        'email' => 'good.row@example.test',
    ]);

    expect(Prospect::where('user_id', $user->id)->count())->toBe(2);
});

test('the prospects list paginates', function () {
    $user = User::factory()->create();

    foreach (range(1, 15) as $i) {
        Prospect::create([
            'user_id' => $user->id,
            'first_name' => "Lead{$i}",
            'last_name' => 'Test',
            'email' => "lead{$i}@example.test",
        ]);
    }

    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->set('perPage', 10)
        ->assertViewHas('rows', function ($rows) {
            return $rows->total() === 15 && $rows->count() === 10;
        });
});

test('opening the activity log loads only that prospect, scoped to its owner', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    $prospect = Prospect::create(['user_id' => $user->id, 'first_name' => 'Jane', 'last_name' => 'Doe']);
    $othersProspect = Prospect::create(['user_id' => $other->id, 'first_name' => 'Not', 'last_name' => 'Mine']);

    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->call('openActivityLog', $prospect->id)
        ->assertSet('showActivityModal', true)
        ->assertSet('activityProspectId', $prospect->id)
        ->assertSee('Jane Doe')
        ->assertSee('No activity logged yet');

    expect(fn () => Livewire::actingAs($user)->test(ProspectsManager::class)->call('openActivityLog', $othersProspect->id))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
});

test('a new activity can be logged against a prospect and syncs its next follow-up', function () {
    $user = User::factory()->create();
    $prospect = Prospect::create(['user_id' => $user->id, 'first_name' => 'Jane', 'last_name' => 'Doe']);

    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->call('openActivityLog', $prospect->id)
        ->set('activityAction', 'Phone Call')
        ->set('activityNotes', 'Discussed the comp plan.')
        ->set('activityDate', '2026-11-01T10:00')
        ->set('activityNextFollowUp', '2026-11-08T10:00')
        ->call('saveActivity')
        ->assertSee('Phone Call')
        ->assertSee('Discussed the comp plan.');

    $this->assertDatabaseHas('timelines', [
        'prospect_id' => $prospect->id,
        'action' => 'Phone Call',
        'notes' => 'Discussed the comp plan.',
    ]);

    expect($prospect->fresh()->next_follow_up->format('Y-m-d H:i'))->toBe('2026-11-08 10:00');
});

test('an activity can be edited and the change is persisted, not duplicated', function () {
    $user = User::factory()->create();
    $prospect = Prospect::create(['user_id' => $user->id, 'first_name' => 'Jane', 'last_name' => 'Doe']);
    $activity = Timeline::create([
        'prospect_id' => $prospect->id,
        'action' => 'Presentation',
        'notes' => 'Original notes',
        'action_at' => '2026-10-01 09:00:00',
    ]);

    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->call('openActivityLog', $prospect->id)
        ->call('editActivity', $activity->id)
        ->assertSet('editingActivityId', $activity->id)
        ->assertSet('activityAction', 'Presentation')
        ->set('activityNotes', 'Updated notes')
        ->call('saveActivity');

    expect(Timeline::where('prospect_id', $prospect->id)->count())->toBe(1);
    expect($activity->fresh()->notes)->toBe('Updated notes');
});

test('an activity can be deleted from the log', function () {
    $user = User::factory()->create();
    $prospect = Prospect::create(['user_id' => $user->id, 'first_name' => 'Jane', 'last_name' => 'Doe']);
    $activity = Timeline::create([
        'prospect_id' => $prospect->id,
        'action' => 'Met In Person',
        'action_at' => '2026-10-01 09:00:00',
    ]);

    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->call('openActivityLog', $prospect->id)
        ->call('deleteActivity', $activity->id);

    $this->assertDatabaseMissing('timelines', ['id' => $activity->id]);
});

test('a member cannot delete an activity that belongs to another member\'s prospect', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $othersProspect = Prospect::create(['user_id' => $other->id, 'first_name' => 'Not', 'last_name' => 'Mine']);
    $activity = Timeline::create([
        'prospect_id' => $othersProspect->id,
        'action' => 'Phone Call',
        'action_at' => '2026-10-01 09:00:00',
    ]);

    // Force activityProspectId to point at someone else's prospect, bypassing openActivityLog's own guard.
    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->set('activityProspectId', $othersProspect->id)
        ->call('deleteActivity', $activity->id);

    $this->assertDatabaseHas('timelines', ['id' => $activity->id]);
});

test('each activity type shows its own label and badge in the log', function () {
    $user = User::factory()->create();
    $prospect = Prospect::create(['user_id' => $user->id, 'first_name' => 'Jane', 'last_name' => 'Doe']);

    Timeline::create(['prospect_id' => $prospect->id, 'action' => 'Met In Person', 'action_at' => '2026-10-01 09:00:00']);

    Livewire::actingAs($user)
        ->test(ProspectsManager::class)
        ->call('openActivityLog', $prospect->id)
        ->assertSee('Met In Person');
});
