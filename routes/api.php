<?php

use App\Http\Controllers\Api\VendorAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('vendor')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [VendorAuthController::class, 'register']);
        Route::post('login', [VendorAuthController::class, 'login']);
        Route::post('{vendor:code}/set-password', [VendorAuthController::class, 'setPassword']);
    });
});

