<?php

use App\Models\Vendor;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $vendor = Vendor::first();
    dd($vendor::class === Vendor::class);
    return view('welcome');
});
