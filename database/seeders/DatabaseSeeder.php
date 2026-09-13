<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with initial data.
     *
     * This method runs all default seeders, including the demo product data
     * and a default user that can be used for login testing.
     */
    public function run(): void
    {
        // Seed demo product data.
        $this->call(ProductSeeder::class);

        // Seed a default user for login testing.
        $this->call(UserSeeder::class);
    }
}
