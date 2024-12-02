<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerAuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\VendorAuthController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Middleware\CheckOwnsAddressMiddleware;
use App\Http\Middleware\CheckVendorOwnsCategoryMiddleware;
use App\Http\Middleware\CheckVendorOwnsProductMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('vendor')->group(function () {

    Route::prefix('auth')->controller(VendorAuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('{vendor:code}/set-password', 'setPassword');
    });

    Route::controller(VendorController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{vendor}/menu', 'menu');
        Route::get('/{vendor}', 'show');
    });

    Route::middleware('auth:vendor')->group(function () {
        Route::post('/addresses', [AddressController::class, 'storeVendorAddress']);

        Route::controller(VendorController::class)->group(function () {
            Route::put('/', 'update');
            Route::delete('/', 'destroy');
        });

        Route::prefix('categories')->group(function () {
            Route::post('/', [CategoryController::class, 'store']);
            Route::middleware(CheckVendorOwnsCategoryMiddleware::class)->group(function () {
                Route::put('/{category}', [CategoryController::class, 'update']);
                Route::delete('/{category}', [CategoryController::class, 'destroy']);
            });
        });

        Route::prefix('products')->group(function () {
            Route::post('/', [ProductController::class, 'store']);
            Route::middleware(CheckVendorOwnsProductMiddleware::class)->group(function () {
                Route::put('/{product}', [ProductController::class, 'update']);
                Route::delete('/{product}', [ProductController::class, 'destroy']);
            });
        });
    });

    Route::get('/{vendor}/categories', [CategoryController::class, 'getVendorCategories']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
});

Route::prefix('customers')->group(function () {
    Route::prefix('auth')->controller(CustomerAuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('verifyCode', 'verifyCode');
    });

    Route::middleware('auth:customer')->group(function () {
        Route::put('/', [CustomerController::class, 'update']);
        Route::delete('/', [CustomerController::class, 'destroy']);
        Route::get('/addresses', [AddressController::class, 'customerAddresses']);
        Route::get('/active-addresses', [AddressController::class, 'customerActiveAddresses']);
        Route::post('/addresses', [AddressController::class, 'storeCustomerAddress']);
    });

    Route::get('/{customer}', [CustomerController::class, 'show']);
});

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
