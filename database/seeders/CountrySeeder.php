<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['code' => 'US', 'name' => 'United States', 'emoji' => '🇺🇸'],
            ['code' => 'CA', 'name' => 'Canada', 'emoji' => '🇨🇦'],
            ['code' => 'GB', 'name' => 'United Kingdom', 'emoji' => '🇬🇧'],
            ['code' => 'AU', 'name' => 'Australia', 'emoji' => '🇦🇺'],
            ['code' => 'PH', 'name' => 'Philippines', 'emoji' => '🇵🇭'],
            ['code' => 'SG', 'name' => 'Singapore', 'emoji' => '🇸🇬'],
            ['code' => 'JP', 'name' => 'Japan', 'emoji' => '🇯🇵'],
            ['code' => 'DE', 'name' => 'Germany', 'emoji' => '🇩🇪'],
            ['code' => 'FR', 'name' => 'France', 'emoji' => '🇫🇷'],
            ['code' => 'IN', 'name' => 'India', 'emoji' => '🇮🇳'],
        ];
        DB::table('countries')->insert($countries);
    }
}
