<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);

        return [
            'store_id' => 1,
            'name' => Str::title($name),
            'slug' => Str::slug($name).' - '.Str::lower(Str::random(4)),
            'price' => $this->faker->numberBetween(300, 5000),
            'stock' => $this->faker->numberBetween(0, 50),
            'meta' => [
                'color' => $this->faker->safeColorName(),
                'weight' => $this->faker->numberBetween(250, 1000),
            ],
        ];
    }

    public function zeroStock(): self
    {
        return $this->state(fn () => ['stock' => 0]);
    }
}
