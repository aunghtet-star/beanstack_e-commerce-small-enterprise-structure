<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_wishlist_has_fillable_attributes(): void
    {
        $fillable = [
            'user_id',
            'product_id',
        ];

        $this->assertEquals($fillable, (new Wishlist())->getFillable());
    }

    public function test_wishlist_has_user_relationship(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $wishlist = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $wishlist->user());
        $this->assertEquals(User::class, $wishlist->user()->getRelated()::class);
        $this->assertEquals($user->id, $wishlist->user->id);
    }

    public function test_wishlist_has_product_relationship(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $wishlist = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $wishlist->product());
        $this->assertEquals(Product::class, $wishlist->product()->getRelated()::class);
        $this->assertEquals($product->id, $wishlist->product->id);
    }

    public function test_wishlist_can_be_created(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $wishlist = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertInstanceOf(Wishlist::class, $wishlist);
        $this->assertEquals($user->id, $wishlist->user_id);
        $this->assertEquals($product->id, $wishlist->product_id);
    }
}
