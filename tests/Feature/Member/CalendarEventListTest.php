<?php

use App\Livewire\Member\CalendarEventList;
use App\Livewire\Member\CalendarManager;
use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\CalendarShare;
use App\Models\Prospect;
use App\Models\User;
use Livewire\Livewire;

function makeListComponent(User $user, string $type)
{
    $labels = ['call' => 'Scheduled Phone Calls', 'follow_up' => 'Follow-ups', 'task' => 'Task List', 'event' => 'Events'];
    $icons = ['call' => 'phone', 'follow_up' => 'arrow-path', 'task' => 'check-circle', 'event' => 'calendar'];

    return Livewire::actingAs($user)->test(CalendarEventList::class, [
        'type' => $type,
        'heading' => $labels[$type],
        'icon' => $icons[$type],
    ]);
}

test('each listing table only shows events of its own type, scoped to viewable calendars', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    CalendarEvent::factory()->for($user)->create(['title' => 'Client Call', 'type' => 'call']);
    CalendarEvent::factory()->for($user)->create(['title' => 'Sponsor Check-in', 'type' => 'follow_up']);
    CalendarEvent::factory()->for($user)->create(['title' => 'Finish Report', 'type' => 'task']);
    CalendarEvent::factory()->for($user)->create(['title' => 'Team Meeting', 'type' => 'event']);
    CalendarEvent::factory()->for($other)->create(['title' => 'Someone Elses Call', 'type' => 'call']);

    makeListComponent($user, 'call')->assertSee('Client Call')->assertDontSee('Sponsor Check-in')->assertDontSee('Someone Elses Call');
    makeListComponent($user, 'follow_up')->assertSee('Sponsor Check-in')->assertDontSee('Client Call');
    makeListComponent($user, 'task')->assertSee('Finish Report')->assertDontSee('Team Meeting');
    makeListComponent($user, 'event')->assertSee('Team Meeting')->assertDontSee('Finish Report');
});

test('search and date range filters narrow the list', function () {
    $user = User::factory()->create();

    CalendarEvent::factory()->for($user)->create(['title' => 'Call Acme Corp', 'type' => 'call', 'start_at' => '2026-05-10 09:00:00']);
    CalendarEvent::factory()->for($user)->create(['title' => 'Call Globex Inc', 'type' => 'call', 'start_at' => '2026-06-15 09:00:00']);

    makeListComponent($user, 'call')
        ->set('search', 'Acme')
        ->assertSee('Call Acme Corp')
        ->assertDontSee('Call Globex Inc');

    makeListComponent($user, 'call')
        ->set('from', '2026-06-01')
        ->set('to', '2026-06-30')
        ->assertSee('Call Globex Inc')
        ->assertDontSee('Call Acme Corp');
});

test('the task list can filter by pending vs completed and toggle completion', function () {
    $user = User::factory()->create();

    $pending = CalendarEvent::factory()->for($user)->create(['title' => 'Pending Task', 'type' => 'task']);
    $done = CalendarEvent::factory()->for($user)->create(['title' => 'Done Task', 'type' => 'task', 'completed_at' => now()]);

    makeListComponent($user, 'task')
        ->set('status', 'pending')
        ->assertSee('Pending Task')
        ->assertDontSee('Done Task');

    makeListComponent($user, 'task')
        ->set('status', 'completed')
        ->assertSee('Done Task')
        ->assertDontSee('Pending Task');

    makeListComponent($user, 'task')->call('toggleComplete', $pending->id);
    expect($pending->fresh()->isCompleted())->toBeTrue();

    makeListComponent($user, 'task')->call('toggleComplete', $done->id);
    expect($done->fresh()->isCompleted())->toBeFalse();
});

test('a member with only view access cannot toggle or delete a shared task', function () {
    $owner = User::factory()->create();
    $viewer = User::factory()->create();
    $calendar = Calendar::factory()->for($owner)->create();
    $task = CalendarEvent::factory()->for($owner)->create(['calendar_id' => $calendar->id, 'type' => 'task', 'title' => 'Shared Task']);

    CalendarShare::create(['calendar_id' => $calendar->id, 'shared_with_user_id' => $viewer->id, 'permission' => 'view']);

    makeListComponent($viewer, 'task')->assertSee('Shared Task')->call('toggleComplete', $task->id);
    expect($task->fresh()->isCompleted())->toBeFalse();
});

test('edit and delete buttons dispatch events that the calendar manager modal listens for', function () {
    $user = User::factory()->create();
    $event = CalendarEvent::factory()->for($user)->create(['type' => 'call', 'title' => 'Dispatch Test Call']);

    makeListComponent($user, 'call')
        ->call('edit', $event->id)
        ->assertDispatched('calendar-edit-requested', id: $event->id);

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->dispatch('calendar-edit-requested', id: $event->id)
        ->assertSet('title', 'Dispatch Test Call')
        ->assertSet('showEventModal', true);

    makeListComponent($user, 'call')
        ->call('delete', $event->id)
        ->assertDispatched('calendar-delete-requested', id: $event->id);

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->dispatch('calendar-delete-requested', id: $event->id)
        ->assertSet('deletingId', $event->id);
});

test('the Add button pre-fills the create modal with the right type', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->dispatch('calendar-create-requested', type: 'call')
        ->assertSet('showEventModal', true)
        ->assertSet('type', 'call')
        ->assertSet('editingId', null);
});

test('saving a new call through the modal makes it appear in the calls table', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('openCreate', '2026-07-01', null, false, 'call')
        ->set('title', 'Discovery Call')
        ->call('save');

    makeListComponent($user, 'call')->assertSee('Discovery Call');
});

test('the calls table shows contact and phone columns but not priority or meeting link', function () {
    $user = User::factory()->create();
    $prospect = Prospect::factory()->for($user)->create(['first_name' => 'Jane', 'last_name' => 'Doe']);
    CalendarEvent::factory()->for($user)->create([
        'title' => 'Ring Jane',
        'type' => 'call',
        'prospect_id' => $prospect->id,
        'phone_number' => '555-0177',
    ]);

    makeListComponent($user, 'call')
        ->assertSee('Contact')
        ->assertSee('Phone')
        ->assertDontSee('Priority')
        ->assertSee('Jane Doe')
        ->assertSee('555-0177');
});

test('the follow-ups table shows contact and priority columns but not phone or meeting link', function () {
    $user = User::factory()->create();
    $prospect = Prospect::factory()->for($user)->create(['first_name' => 'Sam', 'last_name' => 'Lee']);
    CalendarEvent::factory()->for($user)->create([
        'title' => 'Check on Sam',
        'type' => 'follow_up',
        'prospect_id' => $prospect->id,
        'priority' => 'high',
    ]);

    makeListComponent($user, 'follow_up')
        ->assertSee('Contact')
        ->assertSee('Priority')
        ->assertDontSee('Phone')
        ->assertSee('Sam Lee')
        ->assertSee('high');
});

test('the task list table shows a priority column but not contact, phone, or meeting link', function () {
    $user = User::factory()->create();
    CalendarEvent::factory()->for($user)->create([
        'title' => 'Prepare slides',
        'type' => 'task',
        'priority' => 'medium',
    ]);

    makeListComponent($user, 'task')
        ->assertSee('Priority')
        ->assertDontSee('Contact')
        ->assertDontSee('Phone')
        ->assertSee('medium');
});

test('the events table shows a join meeting link but no contact, phone, or priority columns', function () {
    $user = User::factory()->create();
    CalendarEvent::factory()->for($user)->create([
        'title' => 'Quarterly Review',
        'type' => 'event',
        'meeting_link' => 'https://zoom.us/j/555',
    ]);

    makeListComponent($user, 'event')
        ->assertDontSee('Contact')
        ->assertDontSee('Phone')
        ->assertDontSee('Priority')
        ->assertSee('Join meeting');
});
