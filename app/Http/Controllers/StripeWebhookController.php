<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly PaymentService $paymentService
    ) {}

    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $event = json_decode($payload, true);

        if (! is_array($event) || ! isset($event['type'])) {
            return response('Invalid payload', 400);
        }

        $type = $event['type'];

        try {
            match ($type) {
                'payment_intent.succeeded' => $this->handlePaymentIntentSucceeded($event),
                'payment_intent.payment_failed' => $this->handlePaymentIntentFailed($event),
                'charge.refunded' => $this->handleChargeRefunded($event),
                default => null,
            };
        } catch (\Throwable $e) {
            Log::error('Stripe webhook error: '.$e->getMessage(), ['event' => $event]);

            return response('Webhook error', 500);
        }

        return response('OK', 200);
    }

    private function handlePaymentIntentSucceeded(array $event): void
    {
        $intent = $event['data']['object'] ?? [];
        $intentId = $intent['id'] ?? null;
        $orderId = $intent['metadata']['order_id'] ?? null;

        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $this->orderService->markOrderPaid($order);
            }
        }

        if ($intentId) {
            $this->paymentService->markPaymentCaptured($intentId);
        }
    }

    private function handlePaymentIntentFailed(array $event): void
    {
        $intent = $event['data']['object'] ?? [];
        $intentId = $intent['id'] ?? null;
        $orderId = $intent['metadata']['order_id'] ?? null;

        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $this->orderService->markOrderCancelled($order);
            }
        }

        if ($intentId) {
            $this->paymentService->markPaymentFailed($intentId);
        }
    }

    private function handleChargeRefunded(array $event): void
    {
        $charge = $event['data']['object'] ?? [];
        $chargeId = $charge['id'] ?? null;

        if ($chargeId) {
            $this->paymentService->markPaymentRefunded($chargeId);
        }
    }
}
