<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Create default users for login and development testing.
     *
     * This seeder is useful for quick local testing when you want a ready-made
     * account without manually creating a user through the application.
     */
    public function run(): void
    {
        // Default admin-like user for login testing.
        User::firstOrCreate(
            ['email' => 'user1@example.com'],
            [
                'name' => 'User One',
                'password' => Hash::make('password1'),
            ]
        );

        // Additional sample user for general testing.
        User::firstOrCreate(
            ['email' => 'user2@example.com'],
            [
                'name' => 'User Two',
                'password' => Hash::make('password2'),
            ]
        );

        // Third sample user to simulate multiple accounts.
        User::firstOrCreate(
            ['email' => 'user3@example.com'],
            [
                'name' => 'User Three',
                'password' => Hash::make('password3'),
            ]
        );
    }
}
