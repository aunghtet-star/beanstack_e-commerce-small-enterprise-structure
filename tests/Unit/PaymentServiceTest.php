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
use Laravel\Cashier\Exceptions\IncompletePayment;
use Illuminate\Support\Facades\Log;

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

    public function test_save_payment_method_as_default_success(): void
    {
        $user = User::factory()->create();
        $paymentMethod = 'pm_test_payment_method';

        // Mock the Cashier method
        $userMock = Mockery::mock($user)->makePartial();
        $userMock->shouldReceive('updateDefaultPaymentMethod')
            ->once()
            ->with($paymentMethod)
            ->andReturn(true);

        $service = new PaymentService();
        $service->savePaymentMethodAsDefault($userMock, $paymentMethod);

        // Test passes if no exception is thrown
        $this->assertTrue(true);
    }

    public function test_save_payment_method_as_default_handles_exceptions(): void
    {
        $user = User::factory()->create();
        $paymentMethod = 'pm_test_payment_method';

        // Mock the Cashier method to throw an exception
        $userMock = Mockery::mock($user)->makePartial();
        $userMock->shouldReceive('updateDefaultPaymentMethod')
            ->once()
            ->with($paymentMethod)
            ->andThrow(new \Exception('Stripe API error'));

        // Mock the Log facade
        Log::shouldReceive('warning')
            ->once()
            ->with('Failed to update default payment method', [
                'user_id' => $user->id,
                'error' => 'Stripe API error',
            ]);

        $service = new PaymentService();
        $service->savePaymentMethodAsDefault($userMock, $paymentMethod);

        // Test passes if no exception is thrown (error is logged but not rethrown)
        $this->assertTrue(true);
    }

    public function test_charge_customer_creates_payment_record_on_success(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'total' => 5000, // $50.00
        ]);
        $amountCents = 5000;
        $paymentMethod = 'pm_test_method';

        // Mock the Cashier Payment object
        $cashierPaymentMock = Mockery::mock(\Laravel\Cashier\Payment::class);
        $cashierPaymentMock->id = 'pi_test_charge_success';

        $userMock = Mockery::mock($user)->makePartial();
        $userMock->shouldReceive('charge')
            ->once()
            ->with($amountCents, $paymentMethod, [
                'currency' => 'usd',
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->number,
                ],
            ])
            ->andReturn($cashierPaymentMock);

        $service = new PaymentService();
        $payment = $service->chargeCustomer($userMock, $amountCents, $paymentMethod, $order);

        // Assert payment record was created
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals($order->id, $payment->order_id);
        $this->assertEquals('stripe', $payment->provider);
        $this->assertEquals('pi_test_charge_success', $payment->provider_id);
        $this->assertEquals(5000, $payment->amount);
        $this->assertEquals('USD', $payment->currency);
        $this->assertEquals('captured', $payment->status);
    }

    public function test_charge_customer_rethrows_incomplete_payment_exception(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create();
        $amountCents = 1000;
        $paymentMethod = 'pm_test_method';

        // Create a mock Cashier Payment object for the exception
        $cashierPaymentMock = Mockery::mock(\Laravel\Cashier\Payment::class);

        // Mock the Cashier charge method to throw IncompletePayment
        $userMock = Mockery::mock($user)->makePartial();
        $userMock->shouldReceive('charge')
            ->once()
            ->andThrow(new IncompletePayment($cashierPaymentMock, 'Payment requires additional action'));

        $service = new PaymentService();

        $this->expectException(IncompletePayment::class);
        $this->expectExceptionMessage('Payment requires additional action');

        $service->chargeCustomer($userMock, $amountCents, $paymentMethod, $order);
    }
}
