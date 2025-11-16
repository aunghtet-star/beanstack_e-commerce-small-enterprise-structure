<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function createOrderFromCart(string $sessionId, string $customerEmail): Order
    {
        $cartItems = CartItem::where('session_id', $sessionId)->with('product')->get();

        if ($cartItems->isEmpty()) {
            throw new \RuntimeException('Cart is empty');
        }

        return DB::transaction(function () use ($cartItems, $customerEmail) {
            $totals = $this->calculateTotals($cartItems);

            $order = Order::create([
                'id' => (string) Str::ulid(),
                'number' => strtoupper(Str::random(12)),
                'customer_email' => $customerEmail,
                'currency' => 'USD',
                'total' => $totals['total_cents'],
                'status' => 'pending',
                'placed_at' => null,
            ]);

            foreach ($cartItems as $item) {
                $this->validateAndReserveStock($item);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'name_snapshot' => $item->product->name,
                    'price_snapshot' => (int) round($item->product->price * 100),
                    'quantity' => $item->quantity,
                ]);

                $this->recordStockMovement($item->product, $item->quantity, 'Order ' . $order->number);
            }

            return $order;
        });
    }

    public function calculateTotals($cartItems): array
    {
        $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);
        $tax = $subtotal * 0.10;
        $shipping = $subtotal >= 100 ? 0 : 10;
        $total = $subtotal + $tax + $shipping;

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
            'total_cents' => (int) round($total * 100),
        ];
    }

    private function validateAndReserveStock(CartItem $item): void
    {
        $product = Product::lockForUpdate()->find($item->product_id);
        
        if (!$product || $product->stock < $item->quantity) {
            throw new \RuntimeException('Insufficient stock for ' . ($product->name ?? 'a product'));
        }

        $product->decrement('stock', $item->quantity);
    }

    private function recordStockMovement(Product $product, int $quantity, string $description): void
    {
        DB::table('stock_movements')->insert([
            'product_id' => $product->id,
            'type' => 'sale',
            'quantity' => $quantity,
            'description' => $description,
            'created_at' => now(),
        ]);
    }

    public function markOrderPaid(Order $order): void
    {
        $order->update([
            'status' => 'paid',
            'placed_at' => now(),
        ]);
    }

    public function markOrderCancelled(Order $order): void
    {
        $order->update(['status' => 'cancelled']);
    }
}
