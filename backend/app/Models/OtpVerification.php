<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    protected $fillable = [
        'phone',
        'otp',
        'expires_at',
        'is_verified',
        'attempts'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_verified' => 'boolean',
        'attempts' => 'integer'
    ];
}
