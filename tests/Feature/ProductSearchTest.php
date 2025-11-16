<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_search_products_by_name()
    {
        Product::factory()->create([
            'name' => 'Blue Cotton T-Shirt',
            'stock' => 10,
        ]);

        Product::factory()->create([
            'name' => 'Red Wool Sweater',
            'stock' => 5,
        ]);

        $response = $this->get(route('products.index', ['search' => 'Blue']));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Products/Index')
            ->has('products.data', 1)
            ->where('filters.search', 'Blue')
        );
    }

    public function test_search_is_case_insensitive()
    {
        Product::factory()->create([
            'name' => 'Blue Cotton T-Shirt',
            'stock' => 10,
        ]);

        $response = $this->get(route('products.index', ['search' => 'blue']));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->has('products.data', 1)
        );
    }

    public function test_search_returns_empty_results_when_no_match()
    {
        Product::factory()->create([
            'name' => 'Blue Cotton T-Shirt',
            'stock' => 10,
        ]);

        $response = $this->get(route('products.index', ['search' => 'NonExistent']));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->has('products.data', 0)
        );
    }

    public function test_products_index_without_search_shows_all_products()
    {
        Product::factory()->count(5)->create(['stock' => 10]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->has('products.data', 5)
        );
    }

    public function test_search_filters_preserve_pagination()
    {
        Product::factory()->count(15)->create([
            'name' => 'Blue Product',
            'stock' => 10,
        ]);

        Product::factory()->count(5)->create([
            'name' => 'Red Product',
            'stock' => 10,
        ]);

        $response = $this->get(route('products.index', ['search' => 'Blue']));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->has('products.data', 12) // First page of paginated results
            ->where('products.total', 15)
        );
    }
}
