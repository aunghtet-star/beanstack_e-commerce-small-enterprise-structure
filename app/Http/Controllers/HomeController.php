<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Display the home page with featured products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get featured products (latest 8 products with stock available)
        $featuredProducts = Product::where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Get product statistics
        $totalProducts = Product::count();
        $productsInStock = Product::where('stock', '>', 0)->count();

        return view('home', [
            'featuredProducts' => $featuredProducts,
            'totalProducts' => $totalProducts,
            'productsInStock' => $productsInStock,
        ]);
    }
}
