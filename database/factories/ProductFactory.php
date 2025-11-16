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

        // Sample product images from Unsplash
        $images = [
            'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=400',
            'https://images.unsplash.com/photo-1525507119028-ed4c629a60a3?w=400',
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400',
            'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=400',
            'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400',
            'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=400',
            'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400',
            'https://images.unsplash.com/photo-1608256246200-53e635b5b65f?w=400',
        ];

        // Product categories
        $categories = ['men', 'women', 'unisex'];

        return [
            'store_id' => 1,
            'name' => Str::title($name),
            'slug' => Str::slug($name).' - '.Str::lower(Str::random(4)),
            'price' => $this->faker->numberBetween(300, 5000),
            'stock' => $this->faker->numberBetween(0, 50),
            'is_featured' => $this->faker->boolean(30), // 30% chance of being featured
            'meta' => [
                'category' => $this->faker->randomElement($categories),
                'color' => $this->faker->safeColorName(),
                'weight' => $this->faker->numberBetween(250, 1000),
                'image_url' => $this->faker->randomElement($images),
            ],
        ];
    }

    public function zeroStock(): self
    {
        return $this->state(fn () => ['stock' => 0]);
    }

    public function featured(): self
    {
        return $this->state(fn () => ['is_featured' => true]);
    }
}
