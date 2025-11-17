<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_has_fillable_attributes(): void
    {
        $fillable = [
            'id',
            'order_id',
            'provider',
            'provider_id',
            'amount',
            'currency',
            'status',
        ];

        $this->assertEquals($fillable, (new Payment())->getFillable());
    }

    public function test_payment_has_correct_key_configuration(): void
    {
        $payment = new Payment();

        $this->assertFalse($payment->incrementing);
        $this->assertEquals('string', $payment->getKeyType());
    }

    public function test_payment_has_order_relationship(): void
    {
        $order = Order::factory()->create();
        $payment = Payment::create([
            'id' => 'pay_test',
            'order_id' => $order->id,
            'provider' => 'stripe',
            'provider_id' => 'pi_test',
            'amount' => 1000,
            'currency' => 'USD',
            'status' => 'captured',
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $payment->order());
        $this->assertEquals(Order::class, $payment->order()->getRelated()::class);
        $this->assertEquals($order->id, $payment->order->id);
    }

    public function test_payment_can_be_created(): void
    {
        $order = Order::factory()->create();
        $payment = Payment::create([
            'id' => 'pay_test_123',
            'order_id' => $order->id,
            'provider' => 'stripe',
            'provider_id' => 'pi_test_123',
            'amount' => 5000,
            'currency' => 'USD',
            'status' => 'captured',
        ]);

        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals('pay_test_123', $payment->id);
        $this->assertEquals($order->id, $payment->order_id);
        $this->assertEquals('stripe', $payment->provider);
        $this->assertEquals(5000, $payment->amount);
        $this->assertEquals('USD', $payment->currency);
        $this->assertEquals('captured', $payment->status);
    }
}
