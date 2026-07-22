<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['user_id', 'name', 'type', 'address_line', 'city', 'zip_code', 'phone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
