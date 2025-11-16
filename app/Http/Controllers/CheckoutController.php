<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Cashier\Exceptions\IncompletePayment;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly OrderService $orderService,
        private readonly PaymentService $paymentService
    ) {
    }

    public function index(Request $request): Response
    {
        $sessionId = $this->cartService->getSessionId();
        $cartItems = CartItem::where('session_id', $sessionId)->with('product')->get();

        $totals = $this->orderService->calculateTotals($cartItems);
        $setupIntent = $request->user()->createSetupIntent();

        return Inertia::render('Checkout/Index', [
            'cartItems' => $cartItems,
            'subtotal' => $totals['subtotal'],
            'tax' => $totals['tax'],
            'shipping' => $totals['shipping'],
            'total' => $totals['total'],
            'stripeKey' => config('services.stripe.key'),
            'setupIntent' => $setupIntent->client_secret,
        ]);
    }

    public function pay(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'save_card' => 'nullable|boolean',
        ]);

        $user = $request->user();
        $sessionId = $this->cartService->getSessionId();

        try {
            // Create order from cart
            $order = $this->orderService->createOrderFromCart($sessionId, $user->email);
            
            // Process payment
            $this->paymentService->chargeCustomer(
                $user,
                $order->total,
                $validated['payment_method'],
                $order
            );

            // Save payment method if requested
            if (!empty($validated['save_card'])) {
                $this->paymentService->savePaymentMethodAsDefault($user, $validated['payment_method']);
            }

            // Mark order as paid
            $this->orderService->markOrderPaid($order);

            // Clear cart
            $this->cartService->clearCart($sessionId);

            return redirect()->route('checkout.success')
                ->with('success', 'Payment successful. Thank you for your order!');
                
        } catch (IncompletePayment $e) {
            return redirect()->route(
                'cashier.payment',
                [$e->payment->id, 'redirect' => route('checkout.success')]
            );
        } catch (\Throwable $e) {
            return back()->withErrors(['payment' => $e->getMessage()]);
        }
    }

    public function success()
    {
        return Inertia::render('Checkout/Success');
    }
}
