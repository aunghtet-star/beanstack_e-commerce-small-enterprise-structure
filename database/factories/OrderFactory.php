<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) \Illuminate\Support\Str::ulid(),
            'number' => 'ORD' . $this->faker->unique()->numberBetween(100000000, 999999999),
            'customer_email' => $this->faker->safeEmail(),
            'currency' => 'USD',
            'total' => $this->faker->numberBetween(1000, 10000), // $10.00 to $100.00
            'status' => 'pending',
            'placed_at' => null,
        ];
    }
}
