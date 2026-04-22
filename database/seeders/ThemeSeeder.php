<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            ['name' => 'Corporate Blue', 'primary_color' => '#003366', 'secondary_color' => '#00509E', 'accent_color' => '#FFD700'],
            ['name' => 'Modern Green', 'primary_color' => '#2E7D32', 'secondary_color' => '#81C784', 'accent_color' => '#FFC107'],
            ['name' => 'Elegant Black', 'primary_color' => '#222222', 'secondary_color' => '#444444', 'accent_color' => '#E91E63'],
            ['name' => 'Startup Orange', 'primary_color' => '#FF6F00', 'secondary_color' => '#FFA040', 'accent_color' => '#00B8D4'],
            ['name' => 'Finance Teal', 'primary_color' => '#008080', 'secondary_color' => '#4DD0E1', 'accent_color' => '#FFEB3B'],
            ['name' => 'Consulting Purple', 'primary_color' => '#6A1B9A', 'secondary_color' => '#BA68C8', 'accent_color' => '#FF7043'],
            ['name' => 'Tech Silver', 'primary_color' => '#607D8B', 'secondary_color' => '#B0BEC5', 'accent_color' => '#00E676'],
            ['name' => 'Legal Navy', 'primary_color' => '#1A237E', 'secondary_color' => '#3949AB', 'accent_color' => '#FBC02D'],
            ['name' => 'Medical Blue', 'primary_color' => '#1976D2', 'secondary_color' => '#90CAF9', 'accent_color' => '#C62828'],
            ['name' => 'Education Red', 'primary_color' => '#C62828', 'secondary_color' => '#FF8A65', 'accent_color' => '#43A047'],
            ['name' => 'Real Estate Gold', 'primary_color' => '#FFD700', 'secondary_color' => '#FFA000', 'accent_color' => '#1976D2'],
            ['name' => 'Retail Pink', 'primary_color' => '#E91E63', 'secondary_color' => '#F8BBD0', 'accent_color' => '#00BCD4'],
            ['name' => 'Logistics Brown', 'primary_color' => '#795548', 'secondary_color' => '#A1887F', 'accent_color' => '#FFB300'],
            ['name' => 'Travel Sky', 'primary_color' => '#0288D1', 'secondary_color' => '#B3E5FC', 'accent_color' => '#FF7043'],
            ['name' => 'Construction Orange', 'primary_color' => '#FF9800', 'secondary_color' => '#FFE0B2', 'accent_color' => '#388E3C'],
            ['name' => 'Hospitality Purple', 'primary_color' => '#8E24AA', 'secondary_color' => '#CE93D8', 'accent_color' => '#FFB300'],
            ['name' => 'Automotive Red', 'primary_color' => '#B71C1C', 'secondary_color' => '#E57373', 'accent_color' => '#1976D2'],
            ['name' => 'Media Blue', 'primary_color' => '#1565C0', 'secondary_color' => '#64B5F6', 'accent_color' => '#FFEB3B'],
            ['name' => 'Nonprofit Green', 'primary_color' => '#388E3C', 'secondary_color' => '#A5D6A7', 'accent_color' => '#FBC02D'],
            ['name' => 'Energy Yellow', 'primary_color' => '#FFEB3B', 'secondary_color' => '#FFF176', 'accent_color' => '#388E3C'],
        ];

        DB::table('themes')->insert($themes);
    }
}
