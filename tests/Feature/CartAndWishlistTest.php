<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartAndWishlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_cart_page()
    {
        $response = $this->get(route('cart.index'));

        $response->assertStatus(200);
    }

    public function test_guest_can_add_product_to_cart()
    {
        $product = Product::factory()->create([
            'stock' => 10,
        ]);

        $response = $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_cannot_add_out_of_stock_product_to_cart()
    {
        $product = Product::factory()->create([
            'stock' => 0,
        ]);

        $response = $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertSessionHasErrors(['product_id']);
    }

    public function test_can_update_cart_item_quantity()
    {
        $product = Product::factory()->create(['stock' => 10]);

        $cartResponse = $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        // Get the cart to find the item ID
        $cart = $this->get(route('cart.index'));
        $cartItem = \App\Models\CartItem::first();

        $response = $this->put(route('cart.update', $cartItem->id), [
            'quantity' => 3,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 3,
        ]);
    }

    public function test_can_remove_item_from_cart()
    {
        $product = Product::factory()->create(['stock' => 10]);

        $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $cartItem = \App\Models\CartItem::first();

        $response = $this->delete(route('cart.destroy', $cartItem->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id,
        ]);
    }

    public function test_authenticated_user_can_view_wishlist()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('wishlist.index'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_view_wishlist()
    {
        $response = $this->get(route('wishlist.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_add_product_to_wishlist()
    {
        $user = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post(route('wishlist.store'), [
            'product_id' => $product->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_cannot_add_same_product_to_wishlist_twice()
    {
        $user = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create();

        // Add product to wishlist first time
        $this->actingAs($user)->post(route('wishlist.store'), [
            'product_id' => $product->id,
        ]);

        // Try to add again
        $response = $this->actingAs($user)->post(route('wishlist.store'), [
            'product_id' => $product->id,
        ]);

        $response->assertSessionHasErrors(['product_id']);
    }

    public function test_can_remove_product_from_wishlist()
    {
        $user = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create();

        // Add to wishlist
        $this->actingAs($user)->post(route('wishlist.store'), [
            'product_id' => $product->id,
        ]);

        $wishlistItem = \App\Models\Wishlist::first();

        $response = $this->actingAs($user)->delete(route('wishlist.destroy', $wishlistItem->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('wishlists', [
            'id' => $wishlistItem->id,
        ]);
    }

    public function test_cart_count_returns_correct_number()
    {
        $product1 = Product::factory()->create(['stock' => 10]);
        $product2 = Product::factory()->create(['stock' => 10]);

        $this->post(route('cart.store'), [
            'product_id' => $product1->id,
            'quantity' => 2,
        ]);

        $this->post(route('cart.store'), [
            'product_id' => $product2->id,
            'quantity' => 3,
        ]);

        $response = $this->get(route('cart.count'));

        $response->assertJson(['count' => 5]); // 2 + 3 = 5 items total
    }

    public function test_wishlist_count_returns_correct_number()
    {
        $user = User::factory()->create(['role' => 'user']);
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        $product3 = Product::factory()->create();

        $this->actingAs($user)->post(route('wishlist.store'), ['product_id' => $product1->id]);
        $this->actingAs($user)->post(route('wishlist.store'), ['product_id' => $product2->id]);
        $this->actingAs($user)->post(route('wishlist.store'), ['product_id' => $product3->id]);

        $response = $this->actingAs($user)->get(route('wishlist.count'));

        $response->assertJson(['count' => 3]);
    }
}
