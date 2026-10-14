<?php

namespace App\Support;

use App\Models\Calendar;
use App\Models\CalendarShare;

/**
 * Shared calendar visibility/edit-permission rules, used by both
 * App\Livewire\Member\CalendarManager (the grid) and
 * App\Livewire\Member\CalendarEventList (the Scheduled Calls / Follow-ups /
 * Tasks / Events tables below it) so the two never disagree about what a
 * member can see or change.
 */
class CalendarAccess
{
    /**
     * Calendars the member can see events from: their own, ones shared with
     * them (any permission), and every system holiday calendar.
     *
     * @return array<int, int>
     */
    public static function viewableCalendarIds(int $userId): array
    {
        $owned = Calendar::where('user_id', $userId)->pluck('id');
        $shared = CalendarShare::where('shared_with_user_id', $userId)->pluck('calendar_id');
        $holidays = Calendar::holidays()->pluck('id');

        return $owned->merge($shared)->merge($holidays)->unique()->values()->all();
    }

    /**
     * Calendars the member can create/edit/delete events in: their own,
     * plus ones shared with them at "edit" permission. Holiday calendars
     * are never editable by anyone.
     *
     * @return array<int, int>
     */
    public static function editableCalendarIds(int $userId): array
    {
        $owned = Calendar::where('user_id', $userId)->pluck('id');
        $editable = CalendarShare::where('shared_with_user_id', $userId)->where('permission', 'edit')->pluck('calendar_id');

        return $owned->merge($editable)->unique()->values()->all();
    }
}
