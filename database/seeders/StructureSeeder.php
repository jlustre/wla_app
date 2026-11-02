<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeds the data the application needs to function regardless of
 * environment: lookup tables, roles, the admin account, and system
 * calendars. No fake/demo records live here — see DemoSeeder for those.
 */
class StructureSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            ThemeSeeder::class,
            CountrySeeder::class,
            StateProvinceSeeder::class,
            TimezoneSeeder::class,
            AppBootstrapSeeder::class,
            LastActionSeeder::class,
            HolidayCalendarSeeder::class,
        ]);
    }
}
