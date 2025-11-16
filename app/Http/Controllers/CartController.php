<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    /**
     * Get or create session ID for cart
     */
    private function getSessionId(): string
    {
        if (! Session::has('cart_session_id')) {
            Session::put('cart_session_id', Session::getId());
        }

        return Session::get('cart_session_id');
    }

    /**
     * Display cart items
     */
    public function index(): Response
    {
        $sessionId = $this->getSessionId();

        $cartItems = CartItem::where('session_id', $sessionId)
            ->with('product')
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return Inertia::render('Cart/Index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'tax' => $subtotal * 0.1, // 10% tax
            'total' => $subtotal * 1.1,
        ]);
    }

    /**
     * Add item to cart
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'exists:products,id',
                function ($attribute, $value, $fail) use ($request) {
                    $product = Product::find($value);
                    if ($product && $product->stock < $request->input('quantity', 1)) {
                        $fail('Product is out of stock or insufficient quantity available.');
                    }
                },
            ],
            'quantity' => 'required|integer|min:1',
        ]);

        $sessionId = $this->getSessionId();
        $product = Product::findOrFail($validated['product_id']);

        // Check if item already in cart
        $cartItem = CartItem::where('session_id', $sessionId)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $validated['quantity'];
            if ($newQuantity > $product->stock) {
                return back()->withErrors(['product_id' => 'Insufficient stock available']);
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'session_id' => $sessionId,
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

        return back()->with('success', 'Product added to cart');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $sessionId = $this->getSessionId();

        if ($cartItem->session_id !== $sessionId) {
            abort(403);
        }

        if ($cartItem->product->stock < $validated['quantity']) {
            return back()->with('error', 'Insufficient stock available');
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return back()->with('success', 'Cart updated');
    }

    /**
     * Remove item from cart
     */
    public function destroy(CartItem $cartItem)
    {
        $sessionId = $this->getSessionId();

        if ($cartItem->session_id !== $sessionId) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart');
    }

    /**
     * Get cart count
     */
    public function count()
    {
        $sessionId = $this->getSessionId();

        $count = CartItem::where('session_id', $sessionId)->sum('quantity');

        return response()->json(['count' => $count]);
    }
}
