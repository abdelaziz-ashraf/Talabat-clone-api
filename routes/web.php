<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/vendors-geoadd', function () {
    $addresses = \App\Models\Address::where('addressable_type', \App\Models\Vendor::class)->get();
    foreach($addresses as $address) {
        \Illuminate\Support\Facades\Redis::command('geoadd', [
            'vendors-locations',
            'longitude' => $address->longitude,
            'latitude' => $address->latitude,
            'vendor_id' => $address->addressable_id,
            'image' => $address->addressable->image,
            'name' => $addresses->addressable->name,
        ]);
    }
});
