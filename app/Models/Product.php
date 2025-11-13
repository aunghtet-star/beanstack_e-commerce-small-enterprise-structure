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
        'meta',
    ];

    /**
     * Attribute casts.
     */
    protected $casts = [
        'meta' => 'array',
    ];
}
