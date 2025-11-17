<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) \Illuminate\Support\Str::ulid(),
            'order_id' => Order::factory(),
            'provider' => 'stripe',
            'provider_id' => 'pi_' . $this->faker->randomNumber(8),
            'amount' => $this->faker->numberBetween(1000, 10000), // Amount in cents
            'currency' => 'USD',
            'status' => 'authorized',
        ];
    }

    public function captured(): self
    {
        return $this->state(fn () => ['status' => 'captured']);
    }

    public function failed(): self
    {
        return $this->state(fn () => ['status' => 'failed']);
    }

    public function refunded(): self
    {
        return $this->state(fn () => ['status' => 'refunded']);
    }
}
