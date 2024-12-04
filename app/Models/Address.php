<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable  = [
        'address', 'city', 'longitude', 'latitude', 'addressable_id', 'addressable_type', 'active'
    ];

    public function addressable() {
        return $this->morphTo();
    }
}
