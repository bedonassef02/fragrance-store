<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Color::create(['name' => 'Moon Gold', 'hex_code' => '#C6A87C']);
        \App\Models\Color::create(['name' => 'Midnight Black', 'hex_code' => '#0F0F0F']); // Slightly off-black for luxury
        \App\Models\Color::create(['name' => 'Pearl White', 'hex_code' => '#F8F8F8']);
        \App\Models\Color::create(['name' => 'Royal Navy', 'hex_code' => '#1A237E']);
    }
}
