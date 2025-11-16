<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // chargeCustomer relies on external Stripe API via Cashier; integration test omitted here.

    public function test_mark_payment_status_transitions(): void
    {
        $payment = Payment::create([
            'id' => 'pay_test',
            'order_id' => Order::create([
                'id' => 'ord_test',
                'number' => 'ORD123456789',
                'customer_email' => 'buyer@example.com',
                'currency' => 'USD',
                'total' => 1000,
                'status' => 'pending',
                'placed_at' => null,
            ])->id,
            'provider' => 'stripe',
            'provider_id' => 'pi_status_test',
            'amount' => 1000,
            'currency' => 'USD',
            'status' => 'captured',
        ]);

        $service = new PaymentService();
        $service->markPaymentFailed('pi_status_test');
        $this->assertEquals('failed', $payment->fresh()->status);

        $service->markPaymentRefunded('pi_status_test');
        $this->assertEquals('refunded', $payment->fresh()->status);

        $service->markPaymentCaptured('pi_status_test');
        $this->assertEquals('captured', $payment->fresh()->status);
    }
}
