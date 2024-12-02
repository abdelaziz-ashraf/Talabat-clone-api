<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Middleware\CheckOwnsAddressMiddleware;
use Illuminate\Support\Facades\Route;

require_once __DIR__ . '/api/vendor.php';
require_once __DIR__ . '/api/customer.php';


Route::middleware(['auth:vendor,customer'])->group(function () {

    Route::prefix('addresses')->group(function () {
        Route::middleware(CheckOwnsAddressMiddleware::class)->group(function () {
            Route::put('/{address}', [AddressController::class, 'update']);
            Route::delete('/{address}', [AddressController::class, 'destroy']);
            Route::patch('/{address}/toggle-active', [AddressController::class, 'toggleActive']);
        });
    });

    Route::prefix('products')->controller(ProductController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{product}', 'show');
    });
});
