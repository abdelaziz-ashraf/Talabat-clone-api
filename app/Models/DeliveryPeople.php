<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class DeliveryPeople extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'phone', 'email', 'password', 'vehicle_type', 'vehicle_number', 'status'
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

}
