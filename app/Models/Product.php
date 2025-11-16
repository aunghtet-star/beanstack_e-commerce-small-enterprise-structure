<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    use HasUlids;

    /**
     * The model is keyed by ULIDs.
     */
    public $incrementing = false;

    /**
     * The primary key type.
     */
    protected $keyType = 'string';

    /**
     * Mass-assignable attributes.
     */
    protected $fillable = [
        'store_id',
        'name',
        'slug',
        'price',
        'stock',
        'is_featured',
        'meta',
    ];

    /**
     * Attribute casts.
     */
    protected $casts = [
        'meta' => 'array',
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the product's image URL.
     */
    public function getImageUrlAttribute()
    {
        return $this->meta['image_url'] ?? 'https://via.placeholder.com/400';
    }

    /**
     * Get the product's category.
     */
    public function getCategoryAttribute()
    {
        return $this->meta['category'] ?? 'Uncategorized';
    }

    /**
     * Get the stock quantity (alias for compatibility).
     */
    public function getStockQuantityAttribute()
    {
        return $this->stock;
    }

    /**
     * Cart items for this product.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Wishlist entries for this product.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
