<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Vendor extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = [
        'name', 'image', 'code', 'password'
    ];

    protected $hidden = [ 'password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    public function address()
    {
        return $this->morphTo(Address::class, 'addressable');
    }

    /*public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }*/

    public function categories() {
        return $this->hasMany(Category::class);
    }

    public function products(){
        return $this->hasManyThrough(Product::class, Category::class);
    }
}
