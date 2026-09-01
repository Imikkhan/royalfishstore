<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_code',
        'name',
        'slug',
        'category_id',
        'sub_category',
        'image',
        'price',
        'original_price',
        'weight',
        'pieces',
        'servings',
        'description',
        'short_description',
        'delivery_time',
        'tags',
        'serviced_pincodes',
        'rating',
        'reviews_count',
        'is_best_seller',
        'is_today_special',
        'is_active',
        'stock_quantity',
        'in_stock',
        'low_stock_threshold',
        'min_order_qty',
        'max_order_qty'
    ];

    protected $casts = [
        'tags' => 'array',
        'serviced_pincodes' => 'array',
        'is_best_seller' => 'boolean',
        'is_today_special' => 'boolean',
        'is_active' => 'boolean',
        'in_stock' => 'boolean',
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'min_order_qty' => 'integer',
        'max_order_qty' => 'integer',
        'price' => 'integer',
        'original_price' => 'integer',
        'rating' => 'float',
        'reviews_count' => 'integer',
    ];

    public function isOutOfStock(): bool
    {
        return !$this->in_stock || $this->stock_quantity <= 0;
    }

    public function isLowStock(): bool
    {
        return $this->in_stock && $this->stock_quantity > 0 && $this->stock_quantity <= ($this->low_stock_threshold ?: 5);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
