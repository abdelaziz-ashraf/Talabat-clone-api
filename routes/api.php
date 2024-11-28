<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\VendorAuthController;
use App\Http\Middleware\CheckOwnsAddressMiddleware;
use App\Http\Middleware\CheckVendorOwnsCategoryMiddleware;
use App\Http\Middleware\CheckVendorOwnsProductMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('vendor')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('register', [VendorAuthController::class, 'register']);
        Route::post('login', [VendorAuthController::class, 'login']);
        Route::post('{vendor:code}/set-password', [VendorAuthController::class, 'setPassword']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/{category}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{category}', [CategoryController::class, 'update'])->middleware(CheckVendorOwnsCategoryMiddleware::class);
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->middleware(CheckVendorOwnsCategoryMiddleware::class);
    });

    Route::prefix('products')->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{product}', [ProductController::class, 'update'])->middleware(CheckVendorOwnsProductMiddleware::class);
        Route::delete('/{product}', [ProductController::class, 'destroy'])->middleware(CheckVendorOwnsProductMiddleware::class);
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

    Route::get('/{vendor}/categories', [CategoryController::class, 'index']);

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{product}', [ProductController::class, 'show']);
    });

});

