<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateProvinceSeeder extends Seeder
{
    public function run(): void
    {
        // Get country IDs
        $usId = DB::table('countries')->where('code', 'US')->value('id');
        $caId = DB::table('countries')->where('code', 'CA')->value('id');

        $statesProvinces = [
            // US States
            ['country_id' => $usId, 'code' => 'AL', 'name' => 'Alabama'],
            ['country_id' => $usId, 'code' => 'AK', 'name' => 'Alaska'],
            ['country_id' => $usId, 'code' => 'AZ', 'name' => 'Arizona'],
            ['country_id' => $usId, 'code' => 'AR', 'name' => 'Arkansas'],
            ['country_id' => $usId, 'code' => 'CA', 'name' => 'California'],
            ['country_id' => $usId, 'code' => 'CO', 'name' => 'Colorado'],
            ['country_id' => $usId, 'code' => 'CT', 'name' => 'Connecticut'],
            ['country_id' => $usId, 'code' => 'DE', 'name' => 'Delaware'],
            ['country_id' => $usId, 'code' => 'FL', 'name' => 'Florida'],
            ['country_id' => $usId, 'code' => 'GA', 'name' => 'Georgia'],
            ['country_id' => $usId, 'code' => 'HI', 'name' => 'Hawaii'],
            ['country_id' => $usId, 'code' => 'ID', 'name' => 'Idaho'],
            ['country_id' => $usId, 'code' => 'IL', 'name' => 'Illinois'],
            ['country_id' => $usId, 'code' => 'IN', 'name' => 'Indiana'],
            ['country_id' => $usId, 'code' => 'IA', 'name' => 'Iowa'],
            ['country_id' => $usId, 'code' => 'KS', 'name' => 'Kansas'],
            ['country_id' => $usId, 'code' => 'KY', 'name' => 'Kentucky'],
            ['country_id' => $usId, 'code' => 'LA', 'name' => 'Louisiana'],
            ['country_id' => $usId, 'code' => 'ME', 'name' => 'Maine'],
            ['country_id' => $usId, 'code' => 'MD', 'name' => 'Maryland'],
            ['country_id' => $usId, 'code' => 'MA', 'name' => 'Massachusetts'],
            ['country_id' => $usId, 'code' => 'MI', 'name' => 'Michigan'],
            ['country_id' => $usId, 'code' => 'MN', 'name' => 'Minnesota'],
            ['country_id' => $usId, 'code' => 'MS', 'name' => 'Mississippi'],
            ['country_id' => $usId, 'code' => 'MO', 'name' => 'Missouri'],
            ['country_id' => $usId, 'code' => 'MT', 'name' => 'Montana'],
            ['country_id' => $usId, 'code' => 'NE', 'name' => 'Nebraska'],
            ['country_id' => $usId, 'code' => 'NV', 'name' => 'Nevada'],
            ['country_id' => $usId, 'code' => 'NH', 'name' => 'New Hampshire'],
            ['country_id' => $usId, 'code' => 'NJ', 'name' => 'New Jersey'],
            ['country_id' => $usId, 'code' => 'NM', 'name' => 'New Mexico'],
            ['country_id' => $usId, 'code' => 'NY', 'name' => 'New York'],
            ['country_id' => $usId, 'code' => 'NC', 'name' => 'North Carolina'],
            ['country_id' => $usId, 'code' => 'ND', 'name' => 'North Dakota'],
            ['country_id' => $usId, 'code' => 'OH', 'name' => 'Ohio'],
            ['country_id' => $usId, 'code' => 'OK', 'name' => 'Oklahoma'],
            ['country_id' => $usId, 'code' => 'OR', 'name' => 'Oregon'],
            ['country_id' => $usId, 'code' => 'PA', 'name' => 'Pennsylvania'],
            ['country_id' => $usId, 'code' => 'RI', 'name' => 'Rhode Island'],
            ['country_id' => $usId, 'code' => 'SC', 'name' => 'South Carolina'],
            ['country_id' => $usId, 'code' => 'SD', 'name' => 'South Dakota'],
            ['country_id' => $usId, 'code' => 'TN', 'name' => 'Tennessee'],
            ['country_id' => $usId, 'code' => 'TX', 'name' => 'Texas'],
            ['country_id' => $usId, 'code' => 'UT', 'name' => 'Utah'],
            ['country_id' => $usId, 'code' => 'VT', 'name' => 'Vermont'],
            ['country_id' => $usId, 'code' => 'VA', 'name' => 'Virginia'],
            ['country_id' => $usId, 'code' => 'WA', 'name' => 'Washington'],
            ['country_id' => $usId, 'code' => 'WV', 'name' => 'West Virginia'],
            ['country_id' => $usId, 'code' => 'WI', 'name' => 'Wisconsin'],
            ['country_id' => $usId, 'code' => 'WY', 'name' => 'Wyoming'],
            // Canada Provinces and Territories
            ['country_id' => $caId, 'code' => 'AB', 'name' => 'Alberta'],
            ['country_id' => $caId, 'code' => 'BC', 'name' => 'British Columbia'],
            ['country_id' => $caId, 'code' => 'MB', 'name' => 'Manitoba'],
            ['country_id' => $caId, 'code' => 'NB', 'name' => 'New Brunswick'],
            ['country_id' => $caId, 'code' => 'NL', 'name' => 'Newfoundland and Labrador'],
            ['country_id' => $caId, 'code' => 'NS', 'name' => 'Nova Scotia'],
            ['country_id' => $caId, 'code' => 'NT', 'name' => 'Northwest Territories'],
            ['country_id' => $caId, 'code' => 'NU', 'name' => 'Nunavut'],
            ['country_id' => $caId, 'code' => 'ON', 'name' => 'Ontario'],
            ['country_id' => $caId, 'code' => 'PE', 'name' => 'Prince Edward Island'],
            ['country_id' => $caId, 'code' => 'QC', 'name' => 'Quebec'],
            ['country_id' => $caId, 'code' => 'SK', 'name' => 'Saskatchewan'],
            ['country_id' => $caId, 'code' => 'YT', 'name' => 'Yukon'],
        ];
        DB::table('states_provinces')->insert($statesProvinces);
    }
}
