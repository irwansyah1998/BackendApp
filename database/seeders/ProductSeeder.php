<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'Apel',
            'price' => 'user1@example.com',
            'description' => '1000',
        ]);
        User::create([
            'name' => 'Jeruk',
            'price' => 'user1@example.com',
            'description' => '1000',
        ]);
        User::create([
            'name' => 'Mangga',
            'price' => 'user1@example.com',
            'description' => '1000',
        ]);
    }
}
