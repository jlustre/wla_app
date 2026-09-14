<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $password = Hash::make('Password123!');
        $userIds = [];

        // Create 50 users
        for ($i = 0; $i < 50; $i++) {
            $user = User::create([
                'username' => $faker->unique()->userName(),
                'email' => $faker->unique()->safeEmail(),
                'password' => $password,
                'status' => 'active',
            ]);
            $userIds[] = $user->id;
        }

        // Assign sponsors in a spread-out, continuous hierarchy (like a balanced tree)
        $users = array_values(User::orderBy('id')->get()->all());
        $userCount = count($userIds);
        $branching = 3; // Each sponsor can have up to 3 direct referrals for a balanced spread
        for ($i = 1; $i < $userCount; $i++) {
            // Find sponsor index (parent in a k-ary tree)
            $sponsorIndex = intval(floor(($i - 1) / $branching));
            $users[$i]->sponsor_id = $users[$sponsorIndex]->id;
            $users[$i]->save();
        }

        
    }
}
