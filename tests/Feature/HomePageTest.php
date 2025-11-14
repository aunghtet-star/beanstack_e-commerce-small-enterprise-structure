<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the home page loads successfully.
     */
    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    /**
     * Test that the home page displays featured products.
     */
    public function test_home_page_displays_featured_products(): void
    {
        // Create some test products
        $products = Product::factory()->count(5)->create([
            'stock' => 10,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        // Assert that products are passed to the view
        $response->assertViewHas('featuredProducts');

        // Assert that product names appear on the page
        foreach ($products as $product) {
            $response->assertSee($product->name);
            $response->assertSee(number_format($product->price, 2));
        }
    }

    /**
     * Test that the home page only shows products with stock.
     */
    public function test_home_page_only_shows_products_with_stock(): void
    {
        // Create products with stock
        $productInStock = Product::factory()->create([
            'name' => 'Product In Stock',
            'stock' => 5,
        ]);

        // Create products without stock
        $productOutOfStock = Product::factory()->create([
            'name' => 'Product Out Of Stock',
            'stock' => 0,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($productInStock->name);
        $response->assertDontSee($productOutOfStock->name);
    }

    /**
     * Test that the home page displays correct product statistics.
     */
    public function test_home_page_displays_product_statistics(): void
    {
        // Create mix of products
        Product::factory()->count(10)->create(['stock' => 10]);
        Product::factory()->count(3)->create(['stock' => 0]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('totalProducts', 13);
        $response->assertViewHas('productsInStock', 10);

        // Check if statistics appear on the page
        $response->assertSee('13'); // total products
        $response->assertSee('10'); // products in stock
    }

    /**
     * Test that the home page limits featured products to 8.
     */
    public function test_home_page_limits_featured_products_to_eight(): void
    {
        // Create 15 products with stock
        Product::factory()->count(15)->create(['stock' => 10]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $featuredProducts = $response->viewData('featuredProducts');

        // Assert that only 8 products are shown
        $this->assertCount(8, $featuredProducts);
    }

    /**
     * Test that the home page shows newest products first.
     */
    public function test_home_page_shows_newest_products_first(): void
    {
        // Create older product
        $oldProduct = Product::factory()->create([
            'name' => 'Old Product',
            'stock' => 10,
            'created_at' => now()->subDays(5),
        ]);

        // Create newer product
        $newProduct = Product::factory()->create([
            'name' => 'New Product',
            'stock' => 10,
            'created_at' => now(),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $featuredProducts = $response->viewData('featuredProducts');

        // Assert that the newest product comes first
        $this->assertEquals($newProduct->id, $featuredProducts->first()->id);
    }

    /**
     * Test that the home page displays empty state when no products exist.
     */
    public function test_home_page_displays_empty_state_when_no_products(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('No products available at the moment');
    }

    /**
     * Test that the home page shows low stock warning.
     */
    public function test_home_page_shows_low_stock_warning(): void
    {
        $lowStockProduct = Product::factory()->create([
            'name' => 'Low Stock Product',
            'stock' => 3,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($lowStockProduct->name);
        $response->assertSee('Low Stock');
    }

    /**
     * Test that the home page displays product prices correctly.
     */
    public function test_home_page_displays_product_prices_correctly(): void
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 99.99,
            'stock' => 10,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('99.99');
    }

    /**
     * Test that add to cart button is disabled for out of stock products.
     */
    public function test_add_to_cart_button_disabled_for_out_of_stock_products(): void
    {
        $outOfStockProduct = Product::factory()->create([
            'name' => 'Out of Stock Item',
            'stock' => 0,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        // Home page should not list out-of-stock products
        $response->assertDontSee($outOfStockProduct->name);
        $response->assertDontSee('Out of Stock');
    }
}
