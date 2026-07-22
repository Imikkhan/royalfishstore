<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Turn off auto-increment because ID is a string (e.g. ROYAL-123456)
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'total_price',
        'payment_method',
        'address_data',
        'status',
        'estimated_delivery'
    ];

    protected $casts = [
        'address_data' => 'array',
        'total_price' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
