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
        'rider_id',
        'total_price',
        'payment_method',
        'address_data',
        'status',
        'shipment_status',
        'tracking_number',
        'dispatched_at',
        'delivered_at',
        'delivery_notes',
        'estimated_delivery'
    ];

    protected $casts = [
        'address_data' => 'array',
        'total_price' => 'integer',
        'dispatched_at' => 'datetime',
        'delivered_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
