<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Home/Landing Page
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Product Routes
Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
Route::get('/category/{category}', [\App\Http\Controllers\ProductController::class, 'category'])->name('products.category');

// Cart Routes (Public - uses session)
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [\App\Http\Controllers\CartController::class, 'store'])->name('cart.store');
Route::put('/cart/{cartItem}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cartItem}', [\App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/cart/count', [\App\Http\Controllers\CartController::class, 'count'])->name('cart.count');

// Alias routes for compatibility
Route::get('/shop', fn() => redirect()->route('products.index'));
Route::get('/men', fn() => redirect()->route('products.category', 'men'));
Route::get('/women', fn() => redirect()->route('products.category', 'women'));
Route::get('/new-arrivals', fn() => redirect()->route('products.category', 'new-arrivals'));

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard redirects to home for regular users
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');

    // Wishlist Routes (Authenticated)
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [\App\Http\Controllers\WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{wishlist}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::get('/wishlist/count', [\App\Http\Controllers\WishlistController::class, 'count'])->name('wishlist.count');
    Route::post('/wishlist/check', [\App\Http\Controllers\WishlistController::class, 'check'])->name('wishlist.check');
});

// Admin Routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin',
])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('dashboard');
});
