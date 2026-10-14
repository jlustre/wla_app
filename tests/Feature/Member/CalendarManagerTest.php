<?php

use App\Livewire\Member\CalendarManager;
use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\CalendarShare;
use App\Models\Prospect;
use App\Models\User;
use Livewire\Livewire;

test('a single event can be created through the modal', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('openCreate', '2026-11-03', null, false)
        ->assertSet('start_date', '2026-11-03')
        ->set('title', 'Team Sync')
        ->set('start_time', '09:00')
        ->set('end_time', '09:30')
        ->set('color', 'basil')
        ->call('save')
        ->assertSet('showEventModal', false);

    $this->assertDatabaseHas('calendar_events', [
        'user_id' => $user->id,
        'title' => 'Team Sync',
        'color' => 'basil',
        'all_day' => false,
    ]);

    $event = CalendarEvent::where('user_id', $user->id)->where('title', 'Team Sync')->first();
    expect($event->start_at->format('H:i'))->toBe('09:00');
    expect($event->end_at->format('H:i'))->toBe('09:30');
});

test('an all-day event stores start and end of day', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('openCreate', '2026-11-05', '2026-11-06', true)
        ->set('title', 'Conference')
        ->call('save');

    $event = CalendarEvent::where('user_id', $user->id)->where('title', 'Conference')->first();

    expect($event->all_day)->toBeTrue();
    expect($event->start_at->format('Y-m-d H:i:s'))->toBe('2026-11-05 00:00:00');
});

test('creating a recurring event materializes occurrences sharing a series id', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('openCreate', '2026-11-02', null, false)
        ->set('title', 'Weekly Standup')
        ->set('start_time', '08:00')
        ->set('end_time', '08:15')
        ->set('repeat', 'weekly')
        ->set('repeat_until', '2026-11-23')
        ->call('save');

    $events = CalendarEvent::where('user_id', $user->id)->where('title', 'Weekly Standup')->orderBy('start_at')->get();

    // Nov 2, 9, 16, 23 — four weekly occurrences.
    expect($events)->toHaveCount(4);
    expect($events->pluck('series_id')->unique())->toHaveCount(1);
    expect($events->first()->start_at->format('Y-m-d'))->toBe('2026-11-02');
    expect($events->last()->start_at->format('Y-m-d'))->toBe('2026-11-23');
});

test('an event can be edited, scoped to its owner', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    $event = CalendarEvent::factory()->for($user)->create(['title' => 'Original']);
    $othersEvent = CalendarEvent::factory()->for($other)->create(['title' => 'Not Mine']);

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('openEdit', $event->id)
        ->assertSet('title', 'Original')
        ->set('title', 'Renamed')
        ->call('save');

    expect($event->fresh()->title)->toBe('Renamed');

    expect(fn () => Livewire::actingAs($user)->test(CalendarManager::class)->call('openEdit', $othersEvent->id))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
});

test('deleting a non-recurring event removes just that event', function () {
    $user = User::factory()->create();
    $event = CalendarEvent::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('confirmDelete', $event->id)
        ->assertSet('deletingHasSeries', false)
        ->call('delete');

    $this->assertDatabaseMissing('calendar_events', ['id' => $event->id]);
});

test('delete scope can remove this, following, or all events in a series', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('openCreate', '2026-12-01', null, false)
        ->set('title', 'Daily Check-in')
        ->set('repeat', 'daily')
        ->set('repeat_until', '2026-12-05')
        ->call('save');

    $events = CalendarEvent::where('user_id', $user->id)->where('title', 'Daily Check-in')->orderBy('start_at')->get();
    expect($events)->toHaveCount(5);

    $third = $events[2]; // 2026-12-03

    // Delete "this and following" from the 3rd occurrence onward.
    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('confirmDelete', $third->id)
        ->assertSet('deletingHasSeries', true)
        ->set('deleteScope', 'following')
        ->call('delete');

    $remaining = CalendarEvent::where('user_id', $user->id)->where('title', 'Daily Check-in')->orderBy('start_at')->get();
    expect($remaining)->toHaveCount(2);
    expect($remaining->pluck('start_at')->map(fn ($d) => $d->format('Y-m-d'))->all())
        ->toBe(['2026-12-01', '2026-12-02']);
});

test('getEvents returns only the authenticated member events in FullCalendar format within range', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    $inRange = CalendarEvent::factory()->for($user)->create([
        'title' => 'In Range',
        'start_at' => '2026-11-10 09:00:00',
        'end_at' => '2026-11-10 10:00:00',
        'color' => 'grape',
    ]);
    CalendarEvent::factory()->for($user)->create([
        'title' => 'Out Of Range',
        'start_at' => '2027-01-01 09:00:00',
        'end_at' => '2027-01-01 10:00:00',
    ]);
    CalendarEvent::factory()->for($other)->create([
        'title' => 'Someone Else',
        'start_at' => '2026-11-11 09:00:00',
        'end_at' => '2026-11-11 10:00:00',
    ]);

    $component = Livewire::actingAs($user)->test(CalendarManager::class);
    $events = collect($component->instance()->getEvents('2026-11-01T00:00:00', '2026-11-30T00:00:00'));

    // Scoped to the "In Range" event specifically rather than an exact
    // total count, since the member also has their own onboarding calendar
    // reminders (App\Services\OnboardingService) which may or may not fall
    // in this window depending on when the test suite happens to run.
    $match = $events->firstWhere('id', (string) $inRange->id);
    expect($match)->not->toBeNull();
    expect($match['title'])->toBe('In Range');
    expect($match['backgroundColor'])->toBe('#8e24aa');
    expect($events->pluck('title'))->not->toContain('Out Of Range')->not->toContain('Someone Else');
});

test('dragging an event updates its stored times', function () {
    $user = User::factory()->create();
    $event = CalendarEvent::factory()->for($user)->create([
        'start_at' => '2026-11-10 09:00:00',
        'end_at' => '2026-11-10 10:00:00',
        'all_day' => false,
    ]);

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('updateEventTimes', $event->id, '2026-11-12T13:00:00', '2026-11-12T14:00:00', false);

    $event->refresh();
    expect($event->start_at->format('Y-m-d H:i'))->toBe('2026-11-12 13:00');
    expect($event->end_at->format('Y-m-d H:i'))->toBe('2026-11-12 14:00');
});

test('dragging an event into another member account is a no-op', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $othersEvent = CalendarEvent::factory()->for($other)->create([
        'start_at' => '2026-11-10 09:00:00',
    ]);

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('updateEventTimes', $othersEvent->id, '2026-12-01T09:00:00', '2026-12-01T10:00:00', false);

    expect($othersEvent->fresh()->start_at->format('Y-m-d'))->toBe('2026-11-10');
});

// --- Custom calendars -----------------------------------------------------

test('every member gets a default calendar automatically', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)->test(CalendarManager::class);

    $this->assertDatabaseHas('calendars', ['user_id' => $user->id, 'is_default' => true]);
});

test('a member can create, rename, and delete a custom calendar', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('openCreateCalendar')
        ->set('calendarName', 'Work')
        ->set('calendarColor', 'grape')
        ->call('saveCalendar');

    $calendar = Calendar::where('user_id', $user->id)->where('name', 'Work')->first();
    expect($calendar)->not->toBeNull();
    expect($calendar->color)->toBe('grape');

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('openEditCalendar', $calendar->id)
        ->assertSet('calendarName', 'Work')
        ->set('calendarName', 'Personal')
        ->call('saveCalendar');

    expect($calendar->fresh()->name)->toBe('Personal');

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('confirmDeleteCalendar', $calendar->id)
        ->call('deleteCalendar');

    $this->assertDatabaseMissing('calendars', ['id' => $calendar->id]);
});

test('deleting a calendar cascades its events', function () {
    $user = User::factory()->create();

    // Instantiating the component first gives the user their auto-created
    // default calendar, so deleting the second one below isn't blocked by
    // the "must keep at least one calendar" guard.
    Livewire::actingAs($user)->test(CalendarManager::class);

    $calendar = Calendar::factory()->for($user)->create(['is_default' => false, 'name' => 'Side Project']);
    $event = CalendarEvent::factory()->for($user)->create(['calendar_id' => $calendar->id]);

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('confirmDeleteCalendar', $calendar->id)
        ->assertSet('deletingCalendarEventCount', 1)
        ->call('deleteCalendar');

    $this->assertDatabaseMissing('calendars', ['id' => $calendar->id]);
    $this->assertDatabaseMissing('calendar_events', ['id' => $event->id]);
});

test('a member cannot delete their only remaining calendar', function () {
    $user = User::factory()->create();

    $component = Livewire::actingAs($user)->test(CalendarManager::class);
    $onlyCalendar = Calendar::where('user_id', $user->id)->sole();

    $component->call('confirmDeleteCalendar', $onlyCalendar->id)
        ->assertHasErrors('calendar')
        ->assertSet('deletingCalendarId', null);

    $this->assertDatabaseHas('calendars', ['id' => $onlyCalendar->id]);
});

test('a member cannot manage a calendar they do not own', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $calendar = Calendar::factory()->for($owner)->create();

    expect(fn () => Livewire::actingAs($intruder)->test(CalendarManager::class)->call('openEditCalendar', $calendar->id))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
});

// --- Sharing ---------------------------------------------------------------

test('sharing a calendar with view permission lets another member see but not edit its events', function () {
    $owner = User::factory()->create();
    $friend = User::factory()->create(['username' => 'friendly_fred']);
    $calendar = Calendar::factory()->for($owner)->create(['name' => 'Work']);
    $event = CalendarEvent::factory()->for($owner)->create(['calendar_id' => $calendar->id, 'title' => 'Owner Event']);

    Livewire::actingAs($owner)
        ->test(CalendarManager::class)
        ->call('openShare', $calendar->id)
        ->set('sharePermission', 'view')
        ->call('addShare', $friend->id);

    $this->assertDatabaseHas('calendar_shares', [
        'calendar_id' => $calendar->id,
        'shared_with_user_id' => $friend->id,
        'permission' => 'view',
    ]);

    // The friend can see it in their feed...
    $friendComponent = Livewire::actingAs($friend)->test(CalendarManager::class);
    $events = collect($friendComponent->instance()->getEvents('2000-01-01T00:00:00', '2100-01-01T00:00:00'));
    $ownerEvent = $events->firstWhere('title', 'Owner Event');
    expect($ownerEvent)->not->toBeNull();
    expect($ownerEvent['editable'])->toBeFalse();

    // ...can open it in read-only mode...
    $friendComponent->call('openEdit', $event->id)->assertSet('readOnly', true);

    // ...but cannot save changes to it.
    expect(fn () => Livewire::actingAs($friend)->test(CalendarManager::class)->call('openEdit', $event->id)->set('title', 'Hacked')->call('save'))
        ->not->toThrow(\Throwable::class);
    expect($event->fresh()->title)->toBe('Owner Event');

    // ...nor delete it.
    expect(fn () => Livewire::actingAs($friend)->test(CalendarManager::class)->call('confirmDelete', $event->id))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
});

test('sharing a calendar with edit permission lets another member create and modify events in it', function () {
    $owner = User::factory()->create();
    $editor = User::factory()->create();
    $calendar = Calendar::factory()->for($owner)->create(['name' => 'Team']);

    CalendarShare::create([
        'calendar_id' => $calendar->id,
        'shared_with_user_id' => $editor->id,
        'permission' => 'edit',
    ]);

    // The editor can create an event directly in the shared calendar.
    Livewire::actingAs($editor)
        ->test(CalendarManager::class)
        ->call('openCreate', '2026-11-20', null, false)
        ->set('calendarId', $calendar->id)
        ->set('title', 'Added by editor')
        ->call('save');

    $event = CalendarEvent::where('calendar_id', $calendar->id)->where('title', 'Added by editor')->first();
    expect($event)->not->toBeNull();
    expect($event->user_id)->toBe($editor->id);

    // The editor can edit and delete it too.
    Livewire::actingAs($editor)
        ->test(CalendarManager::class)
        ->call('openEdit', $event->id)
        ->assertSet('readOnly', false)
        ->set('title', 'Edited by editor')
        ->call('save');

    expect($event->fresh()->title)->toBe('Edited by editor');

    Livewire::actingAs($editor)
        ->test(CalendarManager::class)
        ->call('confirmDelete', $event->id)
        ->call('delete');

    $this->assertDatabaseMissing('calendar_events', ['id' => $event->id]);
});

test('removing a share revokes access immediately', function () {
    $owner = User::factory()->create();
    $friend = User::factory()->create();
    $calendar = Calendar::factory()->for($owner)->create();
    $event = CalendarEvent::factory()->for($owner)->create(['calendar_id' => $calendar->id, 'title' => 'Formerly Shared Event']);

    $share = CalendarShare::create([
        'calendar_id' => $calendar->id,
        'shared_with_user_id' => $friend->id,
        'permission' => 'view',
    ]);

    Livewire::actingAs($owner)
        ->test(CalendarManager::class)
        ->call('removeShare', $share->id);

    $this->assertDatabaseMissing('calendar_shares', ['id' => $share->id]);

    $events = Livewire::actingAs($friend)->test(CalendarManager::class)
        ->instance()->getEvents('2000-01-01T00:00:00', '2100-01-01T00:00:00');

    // The friend still sees their own (onboarding) events — just not the
    // one that used to be shared with them.
    expect(collect($events)->pluck('title'))->not->toContain('Formerly Shared Event');
});

// --- Holiday calendars -------------------------------------------------

test('holiday calculations land on the correct known dates', function () {
    $us = collect(\App\Support\HolidayCalendar::usHolidays(2026))->keyBy('name');
    $ca = collect(\App\Support\HolidayCalendar::canadaHolidays(2026))->keyBy('name');

    expect($us['Martin Luther King Jr. Day']['date']->toDateString())->toBe('2026-01-19');
    expect($us['Memorial Day']['date']->toDateString())->toBe('2026-05-25');
    expect($us['Labor Day']['date']->toDateString())->toBe('2026-09-07');
    expect($us['Thanksgiving Day']['date']->toDateString())->toBe('2026-11-26');

    expect($ca['Good Friday']['date']->toDateString())->toBe('2026-04-03');
    expect($ca['Victoria Day']['date']->toDateString())->toBe('2026-05-18');
    expect($ca['Thanksgiving (Canada)']['date']->toDateString())->toBe('2026-10-12');
});

function seedHolidayCalendarForTest(string $name = 'Holidays in United States', string $date = '2026-01-01'): CalendarEvent
{
    $calendar = Calendar::create(['user_id' => null, 'type' => 'holiday', 'name' => $name, 'color' => 'basil']);

    return CalendarEvent::create([
        'user_id' => null,
        'calendar_id' => $calendar->id,
        'title' => "New Year's Day",
        'start_at' => $date.' 00:00:00',
        'end_at' => $date.' 23:59:59',
        'all_day' => true,
    ]);
}

test('every member can see holiday calendars without any setup', function () {
    $user = User::factory()->create();
    seedHolidayCalendarForTest('Holidays in United States');
    seedHolidayCalendarForTest('Holidays in Canada');

    $events = Livewire::actingAs($user)->test(CalendarManager::class)
        ->instance()->getEvents('2026-01-01T00:00:00', '2026-01-31T00:00:00');

    $titles = collect($events)->pluck('title');
    // Both the US and Canada "New Year's Day" fall in range, so it should appear twice.
    expect($titles->filter(fn ($t) => $t === "New Year's Day"))->toHaveCount(2);
});

test('holiday events are never editable or draggable', function () {
    $user = User::factory()->create();
    seedHolidayCalendarForTest();

    $events = Livewire::actingAs($user)->test(CalendarManager::class)
        ->instance()->getEvents('2026-01-01T00:00:00', '2026-01-31T00:00:00');

    $newYears = collect($events)->firstWhere('title', "New Year's Day");
    expect($newYears['editable'])->toBeFalse();
    expect($newYears['startEditable'])->toBeFalse();
    expect($newYears['durationEditable'])->toBeFalse();
});

test('a holiday event opens read-only and cannot be edited or deleted', function () {
    $user = User::factory()->create();
    $event = seedHolidayCalendarForTest();

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('openEdit', $event->id)
        ->assertSet('readOnly', true)
        ->assertSet('title', $event->title);

    expect(fn () => Livewire::actingAs($user)->test(CalendarManager::class)->call('confirmDelete', $event->id))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
});

test('holiday calendars never appear as writable options when creating an event', function () {
    $user = User::factory()->create();
    seedHolidayCalendarForTest();
    $holidayIds = Calendar::holidays()->pluck('id');

    $writable = Livewire::actingAs($user)->test(CalendarManager::class)
        ->instance()->writableCalendarsForForm()->pluck('id');

    expect($writable->intersect($holidayIds))->toBeEmpty();
});

test('selecting a prospect on a call auto-fills their phone number', function () {
    $user = User::factory()->create();
    $prospect = Prospect::factory()->for($user)->create(['phone' => '555-0100']);

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('openCreate', '2026-11-03', null, false, 'call')
        ->set('prospectId', $prospect->id)
        ->assertSet('phoneNumber', '555-0100');
});

test('a call is saved with its linked prospect and phone number', function () {
    $user = User::factory()->create();
    $prospect = Prospect::factory()->for($user)->create(['phone' => '555-0100']);

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('openCreate', '2026-11-03', null, false, 'call')
        ->set('title', 'Call Jane')
        ->set('prospectId', $prospect->id)
        ->set('phoneNumber', '555-0199')
        ->call('save');

    $this->assertDatabaseHas('calendar_events', [
        'user_id' => $user->id,
        'title' => 'Call Jane',
        'type' => 'call',
        'prospect_id' => $prospect->id,
        'phone_number' => '555-0199',
        'priority' => null,
        'meeting_link' => null,
    ]);
});

test('a follow-up is saved with a linked prospect and a priority', function () {
    $user = User::factory()->create();
    $prospect = Prospect::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('openCreate', '2026-11-03', null, false, 'follow_up')
        ->set('title', 'Follow up with Jane')
        ->set('prospectId', $prospect->id)
        ->set('priority', 'high')
        ->call('save');

    $this->assertDatabaseHas('calendar_events', [
        'user_id' => $user->id,
        'title' => 'Follow up with Jane',
        'type' => 'follow_up',
        'prospect_id' => $prospect->id,
        'priority' => 'high',
        'phone_number' => null,
        'meeting_link' => null,
    ]);
});

test('a task is saved with a priority but no prospect, phone, or meeting link', function () {
    $user = User::factory()->create();
    $prospect = Prospect::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('openCreate', '2026-11-03', null, false, 'task')
        ->set('title', 'Prepare proposal')
        ->set('prospectId', $prospect->id)
        ->set('priority', 'medium')
        ->call('save');

    $this->assertDatabaseHas('calendar_events', [
        'user_id' => $user->id,
        'title' => 'Prepare proposal',
        'type' => 'task',
        'priority' => 'medium',
        'prospect_id' => null,
        'phone_number' => null,
        'meeting_link' => null,
    ]);
});

test('an event is saved with a meeting link but no prospect, phone, or priority', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('openCreate', '2026-11-03', null, false, 'event')
        ->set('title', 'Quarterly Review')
        ->set('meetingLink', 'https://zoom.us/j/123456')
        ->call('save');

    $this->assertDatabaseHas('calendar_events', [
        'user_id' => $user->id,
        'title' => 'Quarterly Review',
        'type' => 'event',
        'meeting_link' => 'https://zoom.us/j/123456',
        'prospect_id' => null,
        'phone_number' => null,
        'priority' => null,
    ]);
});

test('switching type before saving strips fields that no longer apply', function () {
    $user = User::factory()->create();
    $prospect = Prospect::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('openCreate', '2026-11-03', null, false, 'call')
        ->set('title', 'Became an Event')
        ->set('prospectId', $prospect->id)
        ->set('phoneNumber', '555-0199')
        ->set('type', 'event')
        ->set('meetingLink', 'https://zoom.us/j/999')
        ->call('save');

    $this->assertDatabaseHas('calendar_events', [
        'user_id' => $user->id,
        'title' => 'Became an Event',
        'type' => 'event',
        'meeting_link' => 'https://zoom.us/j/999',
        'prospect_id' => null,
        'phone_number' => null,
    ]);
});

test('a prospect belonging to another member cannot be linked to an event', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $othersProspect = Prospect::factory()->for($other)->create();

    Livewire::actingAs($user)
        ->test(CalendarManager::class)
        ->call('openCreate', '2026-11-03', null, false, 'call')
        ->set('title', 'Sneaky Call')
        ->set('prospectId', $othersProspect->id)
        ->call('save')
        ->assertHasErrors('prospectId');

    $this->assertDatabaseMissing('calendar_events', ['title' => 'Sneaky Call']);
});
