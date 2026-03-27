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
        $user = User::firstOrCreate(
            [
                'email' => 'admin@wla.local',
            ],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // Change after first login
            ]
        );

        $role = Role::where('name', 'super-admin')->first();
        if ($role && !$user->hasRole('super-admin')) {
            $user->assignRole($role);
        }
    }
}
