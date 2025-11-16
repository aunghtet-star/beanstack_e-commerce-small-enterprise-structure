<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index(): Response
    {
        // Get trending products (featured products with stock, limit to 4)
        $trendingProducts = Product::where('is_featured', true)
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Sample testimonials (can be moved to database later)
        $testimonials = [
            [
                'name' => 'Jessica L.',
                'text' => 'The quality is absolutely amazing. My new favorite coat! It\'s stylish, warm, and fits perfectly. Will be shopping here again.',
                'avatar' => null,
            ],
            [
                'name' => 'Michael B.',
                'text' => 'Fast shipping and beautiful packaging. The unboxing experience felt so luxurious. The products are even better in person.',
                'avatar' => null,
            ],
            [
                'name' => 'Sarah K.',
                'text' => 'I\'m in love with the minimalist design. The pieces are so versatile and easy to style. A wardrobe staple for sure!',
                'avatar' => null,
            ],
        ];

        return Inertia::render('Home', [
            'canLogin' => \Illuminate\Support\Facades\Route::has('login'),
            'canRegister' => \Illuminate\Support\Facades\Route::has('register'),
            'trendingProducts' => $trendingProducts,
            'testimonials' => $testimonials,
        ]);
    }
}
