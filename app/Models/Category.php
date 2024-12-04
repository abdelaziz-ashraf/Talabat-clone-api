<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'vendor_id'];

    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }
    public function products() {
        return $this->hasMany(Product::class);
    }
}
