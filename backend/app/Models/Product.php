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
        'tags',
        'serviced_pincodes',
        'rating',
        'reviews_count',
        'is_best_seller',
        'is_today_special',
        'is_active'
    ];

    protected $casts = [
        'tags' => 'array',
        'serviced_pincodes' => 'array',
        'is_best_seller' => 'boolean',
        'is_today_special' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'integer',
        'original_price' => 'integer',
        'rating' => 'float',
        'reviews_count' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
