<?php

use App\Livewire\Member\ProspectFunnel;
use App\Models\Prospect;
use App\Models\Timeline;
use App\Models\User;
use Livewire\Livewire;

test('a newly added prospect starts in the Added stage of the board', function () {
    $user = User::factory()->create();
    $prospect = Prospect::create(['user_id' => $user->id, 'first_name' => 'Jane', 'last_name' => 'Doe']);
    Timeline::create(['prospect_id' => $prospect->id, 'action' => 'Added', 'action_at' => now()]);

    Livewire::actingAs($user)
        ->test(ProspectFunnel::class)
        ->assertSee('Jane Doe')
        ->assertViewHas('board', function ($board) use ($prospect) {
            return collect($board['Added'])->pluck('id')->contains($prospect->id);
        });
});

test('a prospect with no logged activity still appears, defaulting to Added', function () {
    $user = User::factory()->create();
    $prospect = Prospect::create(['user_id' => $user->id, 'first_name' => 'No', 'last_name' => 'Activity']);

    Livewire::actingAs($user)
        ->test(ProspectFunnel::class)
        ->assertViewHas('board', function ($board) use ($prospect) {
            return collect($board['Added'])->pluck('id')->contains($prospect->id);
        });
});

test('the board only shows prospects owned by the current member', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    Prospect::create(['user_id' => $user->id, 'first_name' => 'Mine', 'last_name' => 'Lead']);
    Prospect::create(['user_id' => $other->id, 'first_name' => 'Theirs', 'last_name' => 'Lead']);

    Livewire::actingAs($user)
        ->test(ProspectFunnel::class)
        ->assertSee('Mine Lead')
        ->assertDontSee('Theirs Lead');
});

test('search filters the board to matching prospects', function () {
    $user = User::factory()->create();

    Prospect::create(['user_id' => $user->id, 'first_name' => 'Alpha', 'last_name' => 'Smith']);
    Prospect::create(['user_id' => $user->id, 'first_name' => 'Beta', 'last_name' => 'Jones']);

    Livewire::actingAs($user)
        ->test(ProspectFunnel::class)
        ->set('search', 'Alpha')
        ->assertSee('Alpha Smith')
        ->assertDontSee('Beta Jones');
});

test('requesting a move pre-fills the single action for a one-action stage', function () {
    $user = User::factory()->create();
    $prospect = Prospect::create(['user_id' => $user->id, 'first_name' => 'Jane', 'last_name' => 'Doe']);

    Livewire::actingAs($user)
        ->test(ProspectFunnel::class)
        ->call('requestMove', $prospect->id, 'Presentation')
        ->assertSet('movingProspectId', $prospect->id)
        ->assertSet('movingToStage', 'Presentation')
        ->assertSet('moveAction', 'Presentation');
});

test('requesting a move on the Contacted stage defaults to the first of its two actions', function () {
    $user = User::factory()->create();
    $prospect = Prospect::create(['user_id' => $user->id, 'first_name' => 'Jane', 'last_name' => 'Doe']);

    Livewire::actingAs($user)
        ->test(ProspectFunnel::class)
        ->call('requestMove', $prospect->id, 'Contacted')
        ->assertSet('moveAction', 'Phone Call');
});

test('confirming a move logs a new activity and the prospect advances on the board', function () {
    $user = User::factory()->create();
    $prospect = Prospect::create(['user_id' => $user->id, 'first_name' => 'Jane', 'last_name' => 'Doe']);
    Timeline::create(['prospect_id' => $prospect->id, 'action' => 'Added', 'action_at' => now()]);

    $component = Livewire::actingAs($user)
        ->test(ProspectFunnel::class)
        ->call('requestMove', $prospect->id, 'Joined')
        ->set('moveNotes', 'Signed up on the call.')
        ->call('confirmMove')
        ->assertSet('movingProspectId', null);

    $this->assertDatabaseHas('timelines', [
        'prospect_id' => $prospect->id,
        'action' => 'Joined',
        'notes' => 'Signed up on the call.',
    ]);

    $component->assertViewHas('board', function ($board) use ($prospect) {
        return collect($board['Joined'])->pluck('id')->contains($prospect->id)
            && ! collect($board['Added'])->pluck('id')->contains($prospect->id);
    });
});

test('confirming a move with a next follow-up date syncs the prospect record', function () {
    $user = User::factory()->create();
    $prospect = Prospect::create(['user_id' => $user->id, 'first_name' => 'Jane', 'last_name' => 'Doe']);

    Livewire::actingAs($user)
        ->test(ProspectFunnel::class)
        ->call('requestMove', $prospect->id, 'Followed Up')
        ->set('moveNextFollowUp', '2026-12-01T10:00')
        ->call('confirmMove');

    expect($prospect->fresh()->next_follow_up->format('Y-m-d H:i'))->toBe('2026-12-01 10:00');
});

test('a member cannot request a move for another member\'s prospect', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $othersProspect = Prospect::create(['user_id' => $other->id, 'first_name' => 'Not', 'last_name' => 'Mine']);

    expect(fn () => Livewire::actingAs($user)->test(ProspectFunnel::class)->call('requestMove', $othersProspect->id, 'Joined'))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
});

test('a member cannot confirm a move for another member\'s prospect even if the id is forced', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $othersProspect = Prospect::create(['user_id' => $other->id, 'first_name' => 'Not', 'last_name' => 'Mine']);

    expect(fn () => Livewire::actingAs($user)
        ->test(ProspectFunnel::class)
        ->set('movingProspectId', $othersProspect->id)
        ->set('movingToStage', 'Joined')
        ->set('moveAction', 'Joined')
        ->set('moveDate', now()->format('Y-m-d\TH:i'))
        ->call('confirmMove'))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

    $this->assertDatabaseMissing('timelines', ['prospect_id' => $othersProspect->id]);
});

test('requesting a move for an invalid stage name is a no-op', function () {
    $user = User::factory()->create();
    $prospect = Prospect::create(['user_id' => $user->id, 'first_name' => 'Jane', 'last_name' => 'Doe']);

    Livewire::actingAs($user)
        ->test(ProspectFunnel::class)
        ->call('requestMove', $prospect->id, 'Not A Real Stage')
        ->assertSet('movingProspectId', null);
});

test('the funnel chart counts distinct prospects who have ever reached each stage, excluding Closed', function () {
    $user = User::factory()->create();

    $p1 = Prospect::create(['user_id' => $user->id, 'first_name' => 'One', 'last_name' => 'Lead']);
    $p2 = Prospect::create(['user_id' => $user->id, 'first_name' => 'Two', 'last_name' => 'Lead']);
    $p3 = Prospect::create(['user_id' => $user->id, 'first_name' => 'Three', 'last_name' => 'Lead']);

    foreach ([$p1, $p2, $p3] as $p) {
        Timeline::create(['prospect_id' => $p->id, 'action' => 'Added', 'action_at' => now()]);
    }

    Timeline::create(['prospect_id' => $p1->id, 'action' => 'Phone Call', 'action_at' => now()]);
    Timeline::create(['prospect_id' => $p2->id, 'action' => 'Text / Email', 'action_at' => now()]);
    Timeline::create(['prospect_id' => $p1->id, 'action' => 'Joined', 'action_at' => now()]);
    Timeline::create(['prospect_id' => $p3->id, 'action' => 'Closed', 'action_at' => now()]);

    $component = Livewire::actingAs($user)->test(ProspectFunnel::class);

    $component->assertViewHas('funnelStats', function ($stats) {
        return $stats['stages']['Added']['count'] === 3
            && $stats['stages']['Contacted']['count'] === 2
            && $stats['stages']['Joined']['count'] === 1
            && ! isset($stats['stages']['Closed'])
            && $stats['closed'] === 1
            && $stats['total'] === 3;
    });
});

test('clicking a card\'s details button shows the prospect info and its full activity history', function () {
    $user = User::factory()->create();
    $prospect = Prospect::create([
        'user_id' => $user->id,
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane@example.test',
        'phone' => '555-0100',
        'source' => 'Referral',
        'hotness' => 'Hot',
        'notes' => 'Met at the conference.',
    ]);
    Timeline::create(['prospect_id' => $prospect->id, 'action' => 'Added', 'action_at' => now()->subDays(2)]);
    Timeline::create(['prospect_id' => $prospect->id, 'action' => 'Phone Call', 'notes' => 'Left a voicemail.', 'action_at' => now()->subDay()]);

    Livewire::actingAs($user)
        ->test(ProspectFunnel::class)
        ->call('viewDetails', $prospect->id)
        ->assertSet('viewingProspectId', $prospect->id)
        ->assertSee('jane@example.test')
        ->assertSee('555-0100')
        ->assertSee('Referral')
        ->assertSee('Met at the conference.')
        ->assertSee('Activity History (2)')
        ->assertSee('Phone Call')
        ->assertSee('Left a voicemail.');
});

test('the details popup shows an empty state for a prospect with no logged activity', function () {
    $user = User::factory()->create();
    $prospect = Prospect::create(['user_id' => $user->id, 'first_name' => 'Jane', 'last_name' => 'Doe']);

    Livewire::actingAs($user)
        ->test(ProspectFunnel::class)
        ->call('viewDetails', $prospect->id)
        ->assertSee('Activity History (0)')
        ->assertSee('No activity logged yet');
});

test('the details popup can be closed', function () {
    $user = User::factory()->create();
    $prospect = Prospect::create(['user_id' => $user->id, 'first_name' => 'Jane', 'last_name' => 'Doe']);

    Livewire::actingAs($user)
        ->test(ProspectFunnel::class)
        ->call('viewDetails', $prospect->id)
        ->assertSet('viewingProspectId', $prospect->id)
        ->call('closeDetails')
        ->assertSet('viewingProspectId', null);
});

test('a member cannot view another member\'s prospect details', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $othersProspect = Prospect::create(['user_id' => $other->id, 'first_name' => 'Not', 'last_name' => 'Mine']);

    expect(fn () => Livewire::actingAs($user)->test(ProspectFunnel::class)->call('viewDetails', $othersProspect->id))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
});

test('the prospect funnel page is reachable from the sidebar and renders', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('prospects.funnel'))
        ->assertOk()
        ->assertSee('Prospect Funnel')
        ->assertSee('Conversion Funnel');
});
