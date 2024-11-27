<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\VendorAuthController;
use App\Http\Middleware\CheckOwnsAddressMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('vendor')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('register', [VendorAuthController::class, 'register']);
        Route::post('login', [VendorAuthController::class, 'login']);
        Route::post('{vendor:code}/set-password', [VendorAuthController::class, 'setPassword']);
    });

});

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('addresses')->group(function () {
        Route::get('/', [AddressController::class, 'index']);
        Route::post('/', [AddressController::class, 'store']);
        Route::put('/{address}', [AddressController::class, 'update'])->middleware(CheckOwnsAddressMiddleware::class);
        Route::delete('/{address}', [AddressController::class, 'destroy'])->middleware(CheckOwnsAddressMiddleware::class);
        Route::patch('/{address}/toggle-active', [AddressController::class, 'toggleActive'])->middleware(CheckOwnsAddressMiddleware::class);
    });


});

