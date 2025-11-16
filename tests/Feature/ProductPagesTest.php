<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPagesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function products_index_page_loads_successfully(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Products/Index')
                ->has('products')
        );
    }

    /** @test */
    public function products_index_displays_products_with_stock(): void
    {
        Product::factory()->count(5)->create([
            'is_featured' => false,
            'stock' => 10,
        ]);

        Product::factory()->count(2)->create([
            'is_featured' => false,
            'stock' => 0,
        ]);

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Products/Index')
                ->has('products.data', 5)
        );
    }

    /** @test */
    public function product_detail_page_loads_successfully(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->get("/products/{$product->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Products/Show')
                ->has('product')
                ->where('product.id', $product->id)
                ->where('product.name', $product->name)
        );
    }

    /** @test */
    public function product_detail_page_shows_related_products(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        
        Product::factory()->count(5)->create(['stock' => 10]);

        $response = $this->get("/products/{$product->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Products/Show')
                ->has('relatedProducts')
        );
    }

    /** @test */
    public function category_page_loads_successfully(): void
    {
        $response = $this->get('/category/men');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Products/Index')
                ->has('products')
                ->where('category', 'men')
        );
    }

    /** @test */
    public function shop_route_redirects_to_products_index(): void
    {
        $response = $this->get('/shop');

        $response->assertRedirect('/products');
    }

    /** @test */
    public function men_route_redirects_to_category(): void
    {
        $response = $this->get('/men');

        $response->assertRedirect('/category/men');
    }

    /** @test */
    public function product_not_found_returns_404(): void
    {
        $response = $this->get('/products/non-existent-id');

        $response->assertStatus(404);
    }

    /** @test */
    public function products_are_paginated(): void
    {
        Product::factory()->count(20)->create(['stock' => 10]);

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Products/Index')
                ->has('products.data', 12) // Default pagination is 12
                ->has('products.links')
        );
    }
}
