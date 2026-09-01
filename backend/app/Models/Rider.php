<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Rider extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'vehicle_type',
        'vehicle_number',
        'operating_pincodes',
        'status',
        'earnings_per_delivery',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'operating_pincodes' => 'array',
        'is_active' => 'boolean',
        'earnings_per_delivery' => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
