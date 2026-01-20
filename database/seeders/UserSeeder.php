<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin / Main User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@moon.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $users = [
            ['name' => 'Sarah Ahmed', 'email' => 'sarah@example.com'],
            ['name' => 'Nour El-Sherif', 'email' => 'nour@example.com'],
            ['name' => 'Layla Hassan', 'email' => 'layla@example.com'],
            ['name' => 'Mona Zaki', 'email' => 'mona@example.com'],
            ['name' => 'Hana Ali', 'email' => 'hana@example.com'],
        ];

        foreach ($users as $u) {
            User::create([
                'name' => $u['name'],
                'email' => $u['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }
    }
}
