<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeds fake/demo data used for local development and previews: sample
 * users, companies, prospects, calendars, and dashboard activity. Gated
 * behind SEED_DEMO_DATA — see DatabaseSeeder.
 */
class DemoSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            CompanySeeder::class,
            CompanyReviewDemoSeeder::class,
            DashboardContentSeeder::class,
            ProspectSeeder::class,
            ProspectDemoSeeder::class,
            CalendarEventDemoSeeder::class,
            MemberDashboardDemoSeeder::class,
            MemberProfileDemoSeeder::class,
            ReassignmentDemoSeeder::class,
        ]);
    }
}
