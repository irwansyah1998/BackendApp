<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Apel',
            'price' => 10000,
            'description' => 'Buah apel segar',
        ]);

        Product::create([
            'name' => 'Jeruk',
            'price' => 12000,
            'description' => 'Jeruk manis dan segar',
        ]);

        Product::create([
            'name' => 'Mangga',
            'price' => 15000,
            'description' => 'Mangga harum manis',
        ]);
    }
}
