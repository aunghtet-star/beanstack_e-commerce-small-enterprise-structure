<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderItemModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_item_has_fillable_attributes(): void
    {
        $fillable = [
            'order_id',
            'product_id',
            'name_snapshot',
            'price_snapshot',
            'quantity',
        ];

        $this->assertEquals($fillable, (new OrderItem())->getFillable());
    }

    public function test_order_item_has_no_timestamps(): void
    {
        $orderItem = new OrderItem();

        $this->assertFalse($orderItem->timestamps);
    }

    public function test_order_item_has_order_relationship(): void
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'name_snapshot' => 'Test Product',
            'price_snapshot' => 25.00,
            'quantity' => 2,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $orderItem->order());
        $this->assertEquals(Order::class, $orderItem->order()->getRelated()::class);
        $this->assertEquals($order->id, $orderItem->order->id);
    }

    public function test_order_item_has_product_relationship(): void
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'name_snapshot' => 'Test Product',
            'price_snapshot' => 29.99,
            'quantity' => 1,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $orderItem->product());
        $this->assertEquals(Product::class, $orderItem->product()->getRelated()::class);
        $this->assertEquals($product->id, $orderItem->product->id);
    }

    public function test_order_item_can_be_created(): void
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'name_snapshot' => 'Test Product',
            'price_snapshot' => 19.99,
            'quantity' => 3,
        ]);

        $this->assertInstanceOf(OrderItem::class, $orderItem);
        $this->assertEquals($order->id, $orderItem->order_id);
        $this->assertEquals($product->id, $orderItem->product_id);
        $this->assertEquals('Test Product', $orderItem->name_snapshot);
        $this->assertEquals(19.99, $orderItem->price_snapshot);
        $this->assertEquals(3, $orderItem->quantity);
    }
}
