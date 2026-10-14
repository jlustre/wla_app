<?php

namespace App\Support;

use Illuminate\Support\Carbon;

/**
 * Computes US and Canada public holiday dates for a given year, so the
 * "Holidays in United States" / "Holidays in Canada" system calendars
 * (database/seeders/HolidayCalendarSeeder.php) can be regenerated for any
 * year range without needing a fresh hand-maintained dataset every year.
 */
class HolidayCalendar
{
    /**
     * @return array<int, array{name: string, date: \Illuminate\Support\Carbon}>
     */
    public static function usHolidays(int $year): array
    {
        return [
            ['name' => "New Year's Day", 'date' => Carbon::create($year, 1, 1)],
            ['name' => 'Martin Luther King Jr. Day', 'date' => self::nthWeekdayOfMonth($year, 1, Carbon::MONDAY, 3)],
            ["name" => "Valentine's Day", 'date' => Carbon::create($year, 2, 14)],
            ["name" => "Washington's Birthday (Presidents' Day)", 'date' => self::nthWeekdayOfMonth($year, 2, Carbon::MONDAY, 3)],
            ["name" => "St. Patrick's Day", 'date' => Carbon::create($year, 3, 17)],
            ['name' => 'Memorial Day', 'date' => self::lastWeekdayOfMonth($year, 5, Carbon::MONDAY)],
            ["name" => "Mother's Day", 'date' => self::nthWeekdayOfMonth($year, 5, Carbon::SUNDAY, 2)],
            ['name' => 'Juneteenth National Independence Day', 'date' => Carbon::create($year, 6, 19)],
            ["name" => "Father's Day", 'date' => self::nthWeekdayOfMonth($year, 6, Carbon::SUNDAY, 3)],
            ['name' => 'Independence Day', 'date' => Carbon::create($year, 7, 4)],
            ['name' => 'Labor Day', 'date' => self::nthWeekdayOfMonth($year, 9, Carbon::MONDAY, 1)],
            ['name' => 'Columbus Day', 'date' => self::nthWeekdayOfMonth($year, 10, Carbon::MONDAY, 2)],
            ['name' => 'Halloween', 'date' => Carbon::create($year, 10, 31)],
            ['name' => 'Veterans Day', 'date' => Carbon::create($year, 11, 11)],
            ['name' => 'Thanksgiving Day', 'date' => self::nthWeekdayOfMonth($year, 11, Carbon::THURSDAY, 4)],
            ['name' => 'Christmas Day', 'date' => Carbon::create($year, 12, 25)],
        ];
    }

    /**
     * @return array<int, array{name: string, date: \Illuminate\Support\Carbon}>
     */
    public static function canadaHolidays(int $year): array
    {
        $easterSunday = self::easterSunday($year);

        return [
            ['name' => "New Year's Day", 'date' => Carbon::create($year, 1, 1)],
            ['name' => 'Family Day', 'date' => self::nthWeekdayOfMonth($year, 2, Carbon::MONDAY, 3)],
            ["name" => "St. Patrick's Day", 'date' => Carbon::create($year, 3, 17)],
            ['name' => 'Good Friday', 'date' => $easterSunday->copy()->subDays(2)],
            ['name' => 'Easter Sunday', 'date' => $easterSunday],
            ['name' => 'Victoria Day', 'date' => self::onOrBefore($year, 5, 24, Carbon::MONDAY)],
            ["name" => "Mother's Day", 'date' => self::nthWeekdayOfMonth($year, 5, Carbon::SUNDAY, 2)],
            ["name" => "Father's Day", 'date' => self::nthWeekdayOfMonth($year, 6, Carbon::SUNDAY, 3)],
            ['name' => 'Canada Day', 'date' => Carbon::create($year, 7, 1)],
            ['name' => 'Civic Holiday', 'date' => self::nthWeekdayOfMonth($year, 8, Carbon::MONDAY, 1)],
            ['name' => 'Labour Day', 'date' => self::nthWeekdayOfMonth($year, 9, Carbon::MONDAY, 1)],
            ['name' => 'National Day for Truth and Reconciliation', 'date' => Carbon::create($year, 9, 30)],
            ['name' => 'Thanksgiving (Canada)', 'date' => self::nthWeekdayOfMonth($year, 10, Carbon::MONDAY, 2)],
            ['name' => 'Halloween', 'date' => Carbon::create($year, 10, 31)],
            ['name' => 'Remembrance Day', 'date' => Carbon::create($year, 11, 11)],
            ['name' => 'Christmas Day', 'date' => Carbon::create($year, 12, 25)],
            ['name' => 'Boxing Day', 'date' => Carbon::create($year, 12, 26)],
        ];
    }

    /**
     * The Nth occurrence of a weekday in a month (e.g. 3rd Monday of January).
     */
    protected static function nthWeekdayOfMonth(int $year, int $month, int $weekday, int $n): Carbon
    {
        $first = Carbon::create($year, $month, 1);
        $offset = ($weekday - $first->dayOfWeek + 7) % 7;

        return $first->addDays($offset)->addWeeks($n - 1);
    }

    /**
     * The last occurrence of a weekday in a month (e.g. last Monday of May).
     */
    protected static function lastWeekdayOfMonth(int $year, int $month, int $weekday): Carbon
    {
        $last = Carbon::create($year, $month, 1)->endOfMonth()->startOfDay();
        $offset = ($last->dayOfWeek - $weekday + 7) % 7;

        return $last->subDays($offset);
    }

    /**
     * The given weekday on or before a fixed date (e.g. the Monday on or
     * before May 24th, for Victoria Day).
     */
    protected static function onOrBefore(int $year, int $month, int $day, int $weekday): Carbon
    {
        $date = Carbon::create($year, $month, $day);
        $offset = ($date->dayOfWeek - $weekday + 7) % 7;

        return $date->subDays($offset);
    }

    /**
     * Easter Sunday (Gregorian), via the Anonymous/Meeus algorithm —
     * implemented directly so this doesn't depend on the optional PHP
     * "calendar" extension (not always enabled, e.g. on some Windows builds).
     */
    protected static function easterSunday(int $year): Carbon
    {
        $a = $year % 19;
        $b = intdiv($year, 100);
        $c = $year % 100;
        $d = intdiv($b, 4);
        $e = $b % 4;
        $f = intdiv($b + 8, 25);
        $g = intdiv($b - $f + 1, 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = intdiv($c, 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = intdiv($a + 11 * $h + 22 * $l, 451);
        $month = intdiv($h + $l - 7 * $m + 114, 31);
        $day = (($h + $l - 7 * $m + 114) % 31) + 1;

        return Carbon::create($year, $month, $day);
    }
}
