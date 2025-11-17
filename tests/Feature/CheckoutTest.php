<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Mockery;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_checkout_page_loads_successfully(): void
    {
        // Set up Stripe configuration for testing
        config([
            'services.stripe.key' => 'pk_test_mock_key',
            'services.stripe.secret' => 'sk_test_mock_secret',
        ]);

        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'price' => 29.99]);

        // Add item to cart
        $sessionId = Session::getId();
        CartItem::create([
            'session_id' => $sessionId,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->get('/checkout');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Checkout/Index')
            ->has('cartItems')
            ->has('subtotal')
            ->has('tax')
            ->has('shipping')
            ->has('total')
            ->has('stripeKey')
            ->has('setupIntent')
        );
    }

    public function test_checkout_page_requires_authentication(): void
    {
        $response = $this->get('/checkout');

        $response->assertRedirect('/login');
    }

    public function test_checkout_success_page_loads(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/checkout/success');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Checkout/Success')
        );
    }

    public function test_payment_processing_with_valid_data(): void
    {
        // Set up Stripe configuration for testing
        config([
            'services.stripe.key' => 'pk_test_mock_key',
            'services.stripe.secret' => 'sk_test_mock_secret',
        ]);

        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'price' => 29.99]);

        // Add item to cart
        $sessionId = Session::getId();
        CartItem::create([
            'session_id' => $sessionId,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        // Mock Stripe payment method
        $paymentMethod = 'pm_test_payment_method';

        $response = $this->actingAs($user)->post('/checkout/pay', [
            'payment_method' => $paymentMethod,
            'save_card' => false,
        ]);

        // Should redirect to success page
        $response->assertRedirect('/checkout/success');
    }

    public function test_payment_validation_fails_with_invalid_data(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/checkout/pay', [
            'payment_method' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('payment_method');
    }
}
