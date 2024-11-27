<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable  = [
        'address', 'city', 'longitude', 'latitude', 'addressable_id', 'addressable_type', 'active'
    ];

    public function addressable() {
        return $this->morphTo();
    }
}
