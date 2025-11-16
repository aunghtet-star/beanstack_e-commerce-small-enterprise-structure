<?php

namespace Tests\Unit;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_order_from_cart_persists_items_and_adjusts_stock(): void
    {
        $cartService = new CartService;
        $orderService = new OrderService;
        $sessionId = $cartService->getSessionId();

        $product = Product::factory()->create(['stock' => 10, 'price' => 100]);
        CartItem::create([
            'session_id' => $sessionId,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $order = $orderService->createOrderFromCart($sessionId, 'buyer@example.com');

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals('pending', $order->status);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertEquals(8, $product->fresh()->stock); // Stock decremented
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'sale',
        ]);
    }

    public function test_create_order_from_empty_cart_throws(): void
    {
        $this->expectException(\RuntimeException::class);
        $orderService = new OrderService;
        $orderService->createOrderFromCart('missing-session', 'buyer@example.com');
    }

    public function test_mark_order_paid_and_cancelled(): void
    {
        $cartService = new CartService;
        $orderService = new OrderService;
        $sessionId = $cartService->getSessionId();
        $product = Product::factory()->create(['stock' => 5, 'price' => 50]);
        CartItem::create([
            'session_id' => $sessionId,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $order = $orderService->createOrderFromCart($sessionId, 'buyer@example.com');
        $orderService->markOrderPaid($order);
        $this->assertEquals('paid', $order->fresh()->status);
        $this->assertNotNull($order->fresh()->placed_at);

        $orderService->markOrderCancelled($order);
        $this->assertEquals('cancelled', $order->fresh()->status);
    }
}
