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

    /**
     * Safely parse serviced pincodes as a clean array of strings
     */
    public function getServicedPincodesAttribute($val)
    {
        if (is_array($val)) return array_values(array_filter(array_map('strval', $val)));
        if (empty($val)) return [];
        if (is_string($val)) {
            $decoded = json_decode($val, true);
            if (is_array($decoded)) return array_values(array_filter(array_map('strval', $decoded)));
            if (is_string($decoded)) {
                $second = json_decode($decoded, true);
                if (is_array($second)) return array_values(array_filter(array_map('strval', $second)));
            }
            if (str_contains($val, ',')) {
                return array_values(array_filter(array_map('trim', explode(',', $val))));
            }
            return [trim($val)];
        }
        return [];
    }

    /**
     * Ensure serviced pincodes are always stored as valid JSON array
     */
    public function setServicedPincodesAttribute($val)
    {
        if (is_array($val)) {
            $this->attributes['serviced_pincodes'] = json_encode(array_values(array_filter(array_map('trim', $val))));
        } elseif (is_string($val)) {
            $decoded = json_decode($val, true);
            if (is_array($decoded)) {
                $this->attributes['serviced_pincodes'] = json_encode(array_values(array_filter(array_map('trim', $decoded))));
            } elseif (str_contains($val, ',')) {
                $this->attributes['serviced_pincodes'] = json_encode(array_values(array_filter(array_map('trim', explode(',', $val)))));
            } elseif (!empty(trim($val))) {
                $this->attributes['serviced_pincodes'] = json_encode([trim($val)]);
            } else {
                $this->attributes['serviced_pincodes'] = json_encode([]);
            }
        } else {
            $this->attributes['serviced_pincodes'] = json_encode([]);
        }
    }

    /**
     * Check if product is deliverable to a specific pincode
     */
    public function isDeliverableToPincode(?string $pincode): bool
    {
        if (!$pincode) {
            return false;
        }
        $cleanPin = preg_replace('/\D/', '', $pincode);
        if (strlen($cleanPin) !== 6) {
            return false;
        }

        $pins = $this->serviced_pincodes;
        if (empty($pins)) {
            return false;
        }
        if (in_array('*', $pins)) {
            return true;
        }
        return in_array($cleanPin, $pins);
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
