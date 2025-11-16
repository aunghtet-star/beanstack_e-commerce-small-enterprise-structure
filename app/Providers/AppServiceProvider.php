<?php

namespace App\Providers;

use App\Models\CartItem;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share([
            'cartCount' => function () {
                $sessionId = Session::get('cart_session_id', Session::getId());
                if (! Session::has('cart_session_id')) {
                    Session::put('cart_session_id', $sessionId);
                }

                return CartItem::where('session_id', $sessionId)->sum('quantity');
            },
            'wishlistCount' => function () {
                $user = Auth::user();
                if (! $user) {
                    return 0;
                }

                return Wishlist::where('user_id', $user->id)->count();
            },
        ]);
    }
}
