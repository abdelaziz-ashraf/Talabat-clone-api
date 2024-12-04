<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    Cache::put('key', 'value', now()->addMinutes(60));
    $value = Cache::get('key');

    if ($value) {
        echo "Cached Value: " . $value;
    } else {
        echo "No Cached Value Found!";
    }
});
