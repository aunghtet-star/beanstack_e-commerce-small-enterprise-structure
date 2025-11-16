<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class WishlistController extends Controller
{
    /**
     * Display wishlist items
     */
    public function index(): Response
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->get();

        return Inertia::render('Wishlist/Index', [
            'wishlistItems' => $wishlistItems,
        ]);
    }

    /**
     * Add item to wishlist
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'exists:products,id',
                function ($attribute, $value, $fail) {
                    $exists = Wishlist::where('user_id', Auth::id())
                        ->where('product_id', $value)
                        ->exists();

                    if ($exists) {
                        $fail('Product is already in your wishlist.');
                    }
                },
            ],
        ]);

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
        ]);

        return back()->with('success', 'Product added to wishlist');
    }

    /**
     * Remove item from wishlist
     */
    public function destroy(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403);
        }

        $wishlist->delete();

        return back()->with('success', 'Item removed from wishlist');
    }

    /**
     * Check if product is in wishlist
     */
    public function check(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $inWishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->exists();

        return response()->json(['inWishlist' => $inWishlist]);
    }

    /**
     * Get wishlist count
     */
    public function count()
    {
        $count = Wishlist::where('user_id', Auth::id())->count();

        return response()->json(['count' => $count]);
    }
}
