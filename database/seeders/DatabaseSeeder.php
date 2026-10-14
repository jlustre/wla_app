<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ThemeSeeder::class,
            CountrySeeder::class,
            StateProvinceSeeder::class,
            TimezoneSeeder::class,
            AppBootstrapSeeder::class,
            UsersSeeder::class,
            CompanySeeder::class,
            CompanyReviewDemoSeeder::class,
            DashboardContentSeeder::class,
            LastActionSeeder::class,
            ProspectSeeder::class,
            ProspectDemoSeeder::class,
            CalendarEventDemoSeeder::class,
            HolidayCalendarSeeder::class,
            MemberDashboardDemoSeeder::class,
            MemberProfileDemoSeeder::class,
            ReassignmentDemoSeeder::class,
        ]);

    }
}
