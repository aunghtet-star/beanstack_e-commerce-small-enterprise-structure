<?php

namespace Tests\Unit;

use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_session_id_sets_and_returns_value(): void
    {
        $service = new CartService;
        $id = $service->getSessionId();
        $this->assertNotEmpty($id);
        $this->assertEquals($id, $service->getSessionId());
    }

    public function test_clear_cart_removes_items(): void
    {
        $service = new CartService;
        $sessionId = $service->getSessionId();
        $product = Product::factory()->create(['stock' => 10]);
        CartItem::create([
            'session_id' => $sessionId,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->assertDatabaseCount('cart_items', 1);
        $service->clearCart($sessionId);
        $this->assertDatabaseCount('cart_items', 0);
    }
}
