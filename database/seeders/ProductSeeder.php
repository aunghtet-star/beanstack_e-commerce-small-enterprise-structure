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
        // Create 20 regular products
        Product::factory()->count(20)->create();

        // Create 6 featured products with stock
        Product::factory()
            ->count(6)
            ->featured()
            ->create([
                'stock' => fake()->numberBetween(10, 50),
            ]);
    }
}
