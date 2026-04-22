<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Superadmin user
        $user = User::firstOrCreate(
            [
                'email' => 'admin@wla.local',
            ],
            [
                'username' => 'superadmin',
                'password' => Hash::make('password'), // Change after first login
                'status' => 'active',
                'avatar_path' => null,
                'sponsor_id' => null,
            ]
        );

        // Add jlustre user
        $jlustre = User::firstOrCreate(
            [
                'email' => 'jclustre@gmail.com',
            ],
            [
                'username' => 'jlustre',
                'password' => Hash::make('password'),
                'status' => 'active',
                'avatar_path' => null,
                'sponsor_id' => null,
            ]
        );

        $role = Role::where('name', 'super-admin')->first();
        if ($role && !$user->hasRole('super-admin')) {
            $user->assignRole($role);
        }
        // Ensure profile exists for admin user
        if (!$user->profile) {
            $user->profile()->create([
                'bio' => 'Super admin profile',
                'avatar' => null,
            ]);
        }
    }
}
