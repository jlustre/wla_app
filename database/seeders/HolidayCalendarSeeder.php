<?php

namespace Database\Seeders;

use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Support\HolidayCalendar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Two system-wide, read-only calendars — "Holidays in United States" and
 * "Holidays in Canada" — visible to every member (app/Livewire/Member/
 * CalendarManager.php includes every `type = 'holiday'` calendar in
 * viewableCalendarIds() for all users, and never in editableCalendarIds()).
 *
 * Covers a rolling window of years so the calendars stay useful without
 * needing to be reseeded every January. Safe to re-run: it regenerates the
 * holiday events for that window each time rather than accumulating rows.
 */
class HolidayCalendarSeeder extends Seeder
{
    public function run(): void
    {
        $years = range(Carbon::now()->year - 1, Carbon::now()->year + 3);

        $this->seedCountry('Holidays in United States', 'basil', fn (int $year) => HolidayCalendar::usHolidays($year), $years);
        $this->seedCountry('Holidays in Canada', 'tomato', fn (int $year) => HolidayCalendar::canadaHolidays($year), $years);
    }

    protected function seedCountry(string $name, string $color, callable $holidaysForYear, array $years): void
    {
        $calendar = Calendar::firstOrCreate(
            ['type' => 'holiday', 'name' => $name],
            ['user_id' => null, 'color' => $color, 'is_default' => false]
        );

        CalendarEvent::where('calendar_id', $calendar->id)->delete();

        $rows = [];

        foreach ($years as $year) {
            foreach ($holidaysForYear($year) as $holiday) {
                $date = $holiday['date']->toDateString();

                $rows[] = [
                    'user_id' => null,
                    'calendar_id' => $calendar->id,
                    'title' => $holiday['name'],
                    'description' => null,
                    'location' => null,
                    'start_at' => $date.' 00:00:00',
                    'end_at' => $date.' 23:59:59',
                    'all_day' => true,
                    'color' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            CalendarEvent::insert($chunk);
        }

        $this->command?->info("HolidayCalendarSeeder: seeded {$calendar->name} (".count($rows).' events).');
    }
}
