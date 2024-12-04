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

    public function scopeVendorsAddresses($query){
        return $query->where('active', '=', 1)->where('addressable_type', '=', Vendor::class);
    }
    public function scopeVendorsWithinDistance($query, $latitude, $longitude, $radius = 15)
    {
        return $query->vendorsAddresses()
            ->selectRaw(
                "addressable_id, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
                [$latitude, $longitude, $latitude]
            )
            ->whereRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?",
                [$latitude, $longitude, $latitude, $radius]
            )
            ->pluck('addressable_id');
    }
}
