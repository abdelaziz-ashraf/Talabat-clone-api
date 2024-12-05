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
        ]);
    }
    dd(\Illuminate\Support\Facades\Redis::command('zrange', ['vendors-locations', 0, -1]));
});
