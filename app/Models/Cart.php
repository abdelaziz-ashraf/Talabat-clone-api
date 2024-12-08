<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{

    protected $fillable = ['vendor_id', 'customer_id', 'total_price'];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function vendor() {
        return $this->belongsTo(Vendor::class);
    }

    public function items(){
        return $this->hasMany(CartItem::class);
    }
}
