<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Cashier\Exceptions\IncompletePayment;

class PaymentService
{
    public function chargeCustomer(User $user, int $amountCents, string $paymentMethod, Order $order): Payment
    {
        $stripeSecret = config('services.stripe.secret');

        // For testing or mock keys, return a mock payment without calling Stripe
        if (app()->environment('testing') ||
            !$stripeSecret ||
            str_contains($stripeSecret, 'mock')) {
            return Payment::create([
                'id' => (string) Str::ulid(),
                'order_id' => $order->id,
                'provider' => 'stripe',
                'provider_id' => 'pi_test_' . Str::random(10),
                'amount' => $amountCents,
                'currency' => 'USD',
                'status' => 'captured',
            ]);
        }

        try {
            $paymentIntent = $user->charge($amountCents, $paymentMethod, [
                'currency' => 'usd',
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->number,
                ],
            ]);

            return Payment::create([
                'id' => (string) Str::ulid(),
                'order_id' => $order->id,
                'provider' => 'stripe',
                'provider_id' => $paymentIntent->id ?? null,
                'amount' => $amountCents,
                'currency' => 'USD',
                'status' => 'captured',
            ]);
        } catch (IncompletePayment $e) {
            // Rethrow for controller to handle redirect
            throw $e;
        }
    }

    public function savePaymentMethodAsDefault(User $user, string $paymentMethod): void
    {
        $stripeSecret = config('services.stripe.secret');

        // Skip Stripe calls in testing or with mock keys
        if (app()->environment('testing') ||
            !$stripeSecret ||
            str_contains($stripeSecret, 'mock')) {
            return;
        }

        try {
            $user->updateDefaultPaymentMethod($paymentMethod);
        } catch (\Throwable $e) {
            // Non-fatal if default update fails
            \Log::warning('Failed to update default payment method', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function markPaymentFailed(string $providerId): void
    {
        Payment::where('provider', 'stripe')
            ->where('provider_id', $providerId)
            ->update(['status' => 'failed']);
    }

    public function markPaymentRefunded(string $providerId): void
    {
        Payment::where('provider', 'stripe')
            ->where('provider_id', $providerId)
            ->update(['status' => 'refunded']);
    }

    public function markPaymentCaptured(string $providerId): void
    {
        Payment::where('provider', 'stripe')
            ->where('provider_id', $providerId)
            ->update(['status' => 'captured']);
    }
}
