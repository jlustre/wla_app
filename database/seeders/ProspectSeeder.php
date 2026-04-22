<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Prospect;
use App\Models\User;
use App\Models\Timeline;
use Faker\Factory as Faker;

class ProspectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::all();
        if ($users->count() === 0) {
            $users = collect([User::factory()->create()]);
        }

        // Ensure jlustre exists
        $jlustre = User::where('username', 'jlustre')->first();
        $sources = ['Facebook', 'Referral', 'Instagram', 'YouTube', 'TikTok', 'LinkedIn', 'Twitter', 'family', 'friend', 'other'];
        $hotnesses = ['Hot', 'Warm', 'Cold', 'Inactive', 'N/A'];
        $lastActions = \App\Models\LastAction::all();

        for ($i = 0; $i < 50; $i++) {
            // Assign first 10 prospects to jlustre, rest random
            if ($i < 10 && $jlustre) {
                $user = $jlustre;
            } else {
                $user = $users->random();
            }
            $prospect = Prospect::create([
                'user_id' => $user->id,
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'phone' => $faker->optional()->phoneNumber(),
                'source' => $faker->randomElement($sources),
                'hotness' => $faker->randomElement($hotnesses),
                'next_follow_up' => $faker->optional()->dateTimeBetween('now', '+2 weeks'),
                'last_action_id' => $lastActions->random()->id,
                'notes' => $faker->optional()->paragraph(),
            ]);
            Timeline::create([
                'prospect_id' => $prospect->id,
                'action' => 'Added',
                'notes' => null,
                'action_at' => $prospect->created_at,
            ]);
        }
    }
}
