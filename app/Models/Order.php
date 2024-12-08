<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'customer_id', 'vendor_id', 'status',
        'delivery_fee', 'total_price', 'payment_method', 'delivery_address', 'comments'
    ];

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function vendor() {
        return $this->belongsTo(Vendor::class);
    }
}
