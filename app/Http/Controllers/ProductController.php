<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): Response
    {
        $products = Product::where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return Inertia::render('Products/Index', [
            'products' => $products,
            'title' => 'All Products',
        ]);
    }

    /**
     * Display products by category.
     */
    public function category(string $category): Response
    {
        $products = Product::where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categoryTitle = ucfirst($category);

        return Inertia::render('Products/Index', [
            'products' => $products,
            'title' => $categoryTitle,
            'category' => $category,
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show(string $id): Response
    {
        $product = Product::findOrFail($id);

        // Get related products (same category, excluding current product)
        $relatedProducts = Product::where('stock', '>', 0)
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return Inertia::render('Products/Show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
