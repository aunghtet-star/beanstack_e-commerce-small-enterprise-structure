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
        $query = Product::where('stock', '>', 0);

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'ilike', "%{$searchTerm}%")
                  ->orWhere('meta->category', 'ilike', "%{$searchTerm}%");
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12);

        return Inertia::render('Products/Index', [
            'products' => $products,
            'title' => 'All Products',
            'filters' => $request->only('search'),
        ]);
    }

    /**
     * Display products by category.
     */
    public function category(string $category, Request $request): Response
    {
        $query = Product::where('stock', '>', 0);

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'ilike', "%{$searchTerm}%")
                  ->orWhere('meta->category', 'ilike', "%{$searchTerm}%");
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12);

        $categoryTitle = ucfirst($category);

        return Inertia::render('Products/Index', [
            'products' => $products,
            'title' => $categoryTitle,
            'category' => $category,
            'filters' => $request->only('search'),
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
