<?php

namespace Tests\Unit;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_has_fillable_attributes(): void
    {
        $fillable = [
            'store_id',
            'name',
            'slug',
            'price',
            'stock',
            'is_featured',
            'meta',
        ];

        $this->assertEquals($fillable, (new Product())->getFillable());
    }

    public function test_product_has_correct_key_configuration(): void
    {
        $product = new Product();

        $this->assertFalse($product->incrementing);
        $this->assertEquals('string', $product->getKeyType());
    }

    public function test_product_has_correct_casts(): void
    {
        $casts = [
            'meta' => 'array',
            'price' => 'decimal:2',
            'is_featured' => 'boolean',
        ];

        $this->assertEquals($casts, (new Product())->getCasts());
    }

    public function test_product_image_url_accessor(): void
    {
        $product = Product::create([
            'store_id' => 1,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 29.99,
            'stock' => 100,
            'is_featured' => false,
            'meta' => ['image_url' => 'https://example.com/image.jpg'],
        ]);

        $this->assertEquals('https://example.com/image.jpg', $product->image_url);

        $productWithoutImage = Product::create([
            'store_id' => 1,
            'name' => 'Product Without Image',
            'slug' => 'product-without-image',
            'price' => 19.99,
            'stock' => 50,
            'is_featured' => false,
            'meta' => [],
        ]);

        $this->assertEquals('https://via.placeholder.com/400', $productWithoutImage->image_url);
    }

    public function test_product_category_accessor(): void
    {
        $product = Product::create([
            'store_id' => 1,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 29.99,
            'stock' => 100,
            'is_featured' => false,
            'meta' => ['category' => 'Electronics'],
        ]);

        $this->assertEquals('Electronics', $product->category);

        $productWithoutCategory = Product::create([
            'store_id' => 1,
            'name' => 'Uncategorized Product',
            'slug' => 'uncategorized-product',
            'price' => 9.99,
            'stock' => 25,
            'is_featured' => false,
            'meta' => [],
        ]);

        $this->assertEquals('Uncategorized', $productWithoutCategory->category);
    }

    public function test_product_stock_quantity_accessor(): void
    {
        $product = Product::create([
            'store_id' => 1,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 29.99,
            'stock' => 75,
            'is_featured' => false,
            'meta' => [],
        ]);

        $this->assertEquals(75, $product->stock_quantity);
    }

    public function test_product_has_cart_items_relationship(): void
    {
        $product = Product::create([
            'store_id' => 1,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 29.99,
            'stock' => 100,
            'is_featured' => false,
            'meta' => [],
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $product->cartItems());
    }

    public function test_product_has_wishlists_relationship(): void
    {
        $product = Product::create([
            'store_id' => 1,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 29.99,
            'stock' => 100,
            'is_featured' => false,
            'meta' => [],
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $product->wishlists());
    }

    public function test_product_can_be_created(): void
    {
        $product = Product::create([
            'store_id' => 1,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 29.99,
            'stock' => 100,
            'is_featured' => true,
            'meta' => ['category' => 'Electronics', 'image_url' => 'https://example.com/image.jpg'],
        ]);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals(1, $product->store_id);
        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals('test-product', $product->slug);
        $this->assertEquals(29.99, $product->price);
        $this->assertEquals(100, $product->stock);
        $this->assertTrue($product->is_featured);
        $this->assertEquals(['category' => 'Electronics', 'image_url' => 'https://example.com/image.jpg'], $product->meta);
    }
}
