<?php

namespace Database\Seeders;

use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\CalendarShare;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Realistic multi-calendar data on the primary demo/test account so "My
 * Calendar" (app/Livewire/Member/CalendarManager.php) has something to look
 * at: three calendars (the default "My Calendar", plus custom "Work" and
 * "Personal" calendars), a mix of timed and all-day events, a recurring
 * series, and the "Work" calendar shared with another member so the sharing
 * UI has something to show too. Safe to re-run — clears its own
 * previously-seeded rows first.
 */
class CalendarEventDemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('username', 'jclustre')->first()
            ?? User::where('email', 'jclustre@gmail.com')->first()
            ?? User::first();

        if (! $user) {
            $this->command?->warn('CalendarEventDemoSeeder: no user found, skipping.');

            return;
        }

        $tag = ' [demo-seed]';

        CalendarEvent::where('user_id', $user->id)
            ->where('description', 'like', '%'.$tag)
            ->delete();

        $defaultCalendar = Calendar::firstOrCreate(
            ['user_id' => $user->id, 'is_default' => true],
            ['name' => 'My Calendar', 'color' => 'peacock']
        );

        $workCalendar = Calendar::firstOrCreate(
            ['user_id' => $user->id, 'name' => 'Work'],
            ['color' => 'tangerine', 'is_default' => false]
        );

        $personalCalendar = Calendar::firstOrCreate(
            ['user_id' => $user->id, 'name' => 'Personal'],
            ['color' => 'grape', 'is_default' => false]
        );

        $today = Carbon::today();

        CalendarEvent::create([
            'user_id' => $user->id,
            'calendar_id' => $workCalendar->id,
            'title' => 'Sponsor Strategy Call',
            'description' => 'Weekly check-in with the strategic sponsor desk.'.$tag,
            'location' => 'Zoom',
            'start_at' => $today->copy()->addHours(9),
            'end_at' => $today->copy()->addHours(9)->addMinutes(30),
            'all_day' => false,
            'reminder_minutes' => 10,
        ]);

        CalendarEvent::create([
            'user_id' => $user->id,
            'calendar_id' => $workCalendar->id,
            'title' => 'New Member Onboarding',
            'description' => 'Walk a new direct through the sponsor playbook.'.$tag,
            'location' => 'Member portal',
            'start_at' => $today->copy()->addDay()->setTime(14, 0),
            'end_at' => $today->copy()->addDay()->setTime(15, 0),
            'all_day' => false,
        ]);

        CalendarEvent::create([
            'user_id' => $user->id,
            'calendar_id' => $workCalendar->id,
            'title' => 'Compensation Plan Webinar',
            'description' => 'Company-hosted overview of the compensation plan.'.$tag,
            'start_at' => $today->copy()->addDays(3)->setTime(18, 0),
            'end_at' => $today->copy()->addDays(3)->setTime(19, 30),
            'all_day' => false,
            'reminder_minutes' => 60,
        ]);

        CalendarEvent::create([
            'user_id' => $user->id,
            'calendar_id' => $defaultCalendar->id,
            'title' => 'Regional Conference',
            'description' => 'Multi-day in-person event.'.$tag,
            'start_at' => $today->copy()->addDays(6)->startOfDay(),
            'end_at' => $today->copy()->addDays(7)->endOfDay(),
            'all_day' => true,
        ]);

        CalendarEvent::create([
            'user_id' => $user->id,
            'calendar_id' => $defaultCalendar->id,
            'title' => 'Compliance Reminder: Income Disclosure',
            'description' => 'Review current income disclosure language before posting.'.$tag,
            'start_at' => $today->copy()->subDays(2)->setTime(10, 0),
            'end_at' => $today->copy()->subDays(2)->setTime(10, 30),
            'all_day' => false,
            'reminder_minutes' => 1440,
        ]);

        CalendarEvent::create([
            'user_id' => $user->id,
            'calendar_id' => $personalCalendar->id,
            'title' => "Kid's Soccer Practice",
            'description' => 'Personal commitment, not work-related.'.$tag,
            'start_at' => $today->copy()->addDays(2)->setTime(17, 30),
            'end_at' => $today->copy()->addDays(2)->setTime(18, 30),
            'all_day' => false,
        ]);

        // A recurring weekly team sync on the Work calendar, 6 occurrences.
        $seriesId = (string) Str::uuid();
        $cursor = $today->copy()->next(Carbon::WEDNESDAY)->setTime(11, 0);
        $until = $cursor->copy()->addWeeks(5);
        $rows = [];

        while ($cursor->lte($until)) {
            $rows[] = [
                'user_id' => $user->id,
                'calendar_id' => $workCalendar->id,
                'title' => 'Team Sync',
                'description' => 'Recurring weekly team sync.'.$tag,
                'location' => null,
                'start_at' => $cursor->copy(),
                'end_at' => $cursor->copy()->addMinutes(30),
                'all_day' => false,
                'series_id' => $seriesId,
                'recurrence_frequency' => 'weekly',
                'recurrence_until' => $until->toDateString(),
                'reminder_minutes' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $cursor->addWeek();
        }

        CalendarEvent::insert($rows);

        // Share the Work calendar with another member so the sharing UI
        // ("Other Calendars" / Share dialog) has something to demonstrate.
        // Cleared and re-picked deterministically each run so this stays
        // idempotent (a random pick would leave stale shares behind).
        CalendarShare::where('calendar_id', $workCalendar->id)->delete();

        $teammate = User::where('id', '!=', $user->id)->orderBy('id')->first();

        if ($teammate) {
            CalendarShare::create([
                'calendar_id' => $workCalendar->id,
                'shared_with_user_id' => $teammate->id,
                'permission' => 'edit',
            ]);
        }

        $this->command?->info('CalendarEventDemoSeeder: seeded demo calendars and events for '.$user->username.'.');
    }
}
