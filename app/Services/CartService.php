<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class CartService
{
    public function getSessionId(): string
    {
        if (!Session::has('cart_session_id')) {
            Session::put('cart_session_id', Session::getId());
        }
        
        return Session::get('cart_session_id');
    }

    public function clearCart(string $sessionId): void
    {
        \App\Models\CartItem::where('session_id', $sessionId)->delete();
    }
}
