<?php

namespace Tests\Unit;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_has_fillable_attributes(): void
    {
        $fillable = [
            'id',
            'number',
            'customer_email',
            'currency',
            'total',
            'status',
            'placed_at',
        ];

        $this->assertEquals($fillable, (new Order())->getFillable());
    }

    public function test_order_has_correct_key_configuration(): void
    {
        $order = new Order();

        $this->assertFalse($order->incrementing);
        $this->assertEquals('string', $order->getKeyType());
    }

    public function test_order_has_correct_casts(): void
    {
        $casts = [
            'total' => 'decimal:2',
            'placed_at' => 'datetime',
        ];

        $this->assertEquals($casts, (new Order())->getCasts());
    }

    public function test_order_has_items_relationship(): void
    {
        $order = Order::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $order->items());
        $this->assertEquals(\App\Models\OrderItem::class, $order->items()->getRelated()::class);
    }

    public function test_order_has_payments_relationship(): void
    {
        $order = Order::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $order->payments());
        $this->assertEquals(\App\Models\Payment::class, $order->payments()->getRelated()::class);
    }

    public function test_order_can_be_created(): void
    {
        $order = Order::create([
            'id' => 'order_123',
            'number' => 'ORD-001',
            'customer_email' => 'test@example.com',
            'currency' => 'USD',
            'total' => 99.99,
            'status' => 'pending',
            'placed_at' => now(),
        ]);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals('order_123', $order->id);
        $this->assertEquals('ORD-001', $order->number);
        $this->assertEquals('test@example.com', $order->customer_email);
        $this->assertEquals('USD', $order->currency);
        $this->assertEquals(99.99, $order->total);
        $this->assertEquals('pending', $order->status);
    }
}
