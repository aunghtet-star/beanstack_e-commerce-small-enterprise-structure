<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Home')
        );
    }

    /** @test */
    public function home_page_displays_featured_products(): void
    {
        // Create featured products with stock
        $featuredProducts = Product::factory()
            ->count(3)
            ->create([
                'is_featured' => true,
                'stock' => 10,
            ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Home')
            ->has('trendingProducts', 3)
        );
    }

    /** @test */
    public function home_page_only_shows_featured_products_with_stock(): void
    {
        // Create featured product with stock
        $featuredWithStock = Product::factory()->create([
            'is_featured' => true,
            'stock' => 5,
        ]);

        // Create featured product without stock
        $featuredOutOfStock = Product::factory()->create([
            'is_featured' => true,
            'stock' => 0,
        ]);

        // Create non-featured product with stock
        $notFeatured = Product::factory()->create([
            'is_featured' => false,
            'stock' => 10,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Home')
            ->has('trendingProducts', 1)
            ->where('trendingProducts.0.id', $featuredWithStock->id)
        );
    }

    /** @test */
    public function home_page_limits_trending_products_to_four(): void
    {
        // Create 10 featured products with stock
        Product::factory()->count(10)->create([
            'is_featured' => true,
            'stock' => 10,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Home')
            ->has('trendingProducts', 4)
        );
    }

    /** @test */
    public function home_page_shows_newest_products_first(): void
    {
        // Create older featured product
        $oldProduct = Product::factory()->create([
            'is_featured' => true,
            'stock' => 10,
            'created_at' => now()->subDays(5),
        ]);

        // Create newer featured product
        $newProduct = Product::factory()->create([
            'is_featured' => true,
            'stock' => 10,
            'created_at' => now(),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Home')
            ->where('trendingProducts.0.id', $newProduct->id)
        );
    }

    /** @test */
    public function home_page_displays_testimonials(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Home')
            ->has('testimonials', 3)
            ->where('testimonials.0.name', 'Jessica L.')
            ->where('testimonials.1.name', 'Michael B.')
            ->where('testimonials.2.name', 'Sarah K.')
        );
    }

    /** @test */
    public function home_page_provides_authentication_flags(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Home')
            ->has('canLogin')
            ->has('canRegister')
        );
    }

    /** @test */
    public function home_page_works_with_no_products(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Home')
            ->has('trendingProducts', 0)
        );
    }
}
