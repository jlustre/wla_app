<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimezoneSeeder extends Seeder
{
    public function run(): void
    {
        $timezones = [
            ['name' => 'America/New_York', 'abbreviation' => 'EST', 'offset' => '-05:00', 'country_code' => 'US'],
            ['name' => 'America/Chicago', 'abbreviation' => 'CST', 'offset' => '-06:00', 'country_code' => 'US'],
            ['name' => 'America/Denver', 'abbreviation' => 'MST', 'offset' => '-07:00', 'country_code' => 'US'],
            ['name' => 'America/Los_Angeles', 'abbreviation' => 'PST', 'offset' => '-08:00', 'country_code' => 'US'],
            ['name' => 'America/Toronto', 'abbreviation' => 'EST', 'offset' => '-05:00', 'country_code' => 'CA'],
            ['name' => 'America/Vancouver', 'abbreviation' => 'PST', 'offset' => '-08:00', 'country_code' => 'CA'],
            ['name' => 'America/Halifax', 'abbreviation' => 'AST', 'offset' => '-04:00', 'country_code' => 'CA'],
            ['name' => 'America/St_Johns', 'abbreviation' => 'NST', 'offset' => '-03:30', 'country_code' => 'CA'],
            ['name' => 'UTC', 'abbreviation' => 'UTC', 'offset' => '+00:00', 'country_code' => null],
            ['name' => 'Asia/Manila', 'abbreviation' => 'PHT', 'offset' => '+08:00', 'country_code' => 'PH'],
        ];
        DB::table('timezones')->insert($timezones);
    }
}
