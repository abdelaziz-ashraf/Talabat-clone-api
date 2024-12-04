<?php

use App\Http\Controllers\Api\Vendor\AuthController;
use Illuminate\Support\Facades\Route;


Route::prefix('vendors')->group(function () {

    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('{vendor:code}/set-password', 'setPassword');
    });

    Route::middleware(['auth:vendor'])->group(function () {

        Route::prefix('profile')
            ->controller(\App\Http\Controllers\Api\Vendor\VendorController::class)
            ->group(function () {
                Route::get('/my-account', 'show');
                Route::get('/menu', 'menu');
                Route::put('/', 'update');
                Route::delete('/', 'destroy');
            });

        Route::prefix('addresses')
            ->controller(\App\Http\Controllers\Api\Vendor\AddressController::class)
            ->group(function () {
                Route::post('/', 'store');
                Route::put('/{address}', 'update');
                Route::delete('/{address}', 'destroy');
                Route::put('/{address}/toggle-active', 'toggleActive');
            });

        Route::prefix('categories')
            ->controller(\App\Http\Controllers\Api\Vendor\CategoryController::class)
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/{category}', 'show');
                Route::post('/', 'store');
                Route::put('/{category}', 'update');
                Route::delete('/{category}', 'destroy');
            });

        Route::prefix('products')
            ->controller(\App\Http\Controllers\Api\Vendor\ProductController::class)
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/{product}', 'show');
                Route::post('/', 'store');
                Route::put('/{product}', 'update');
                Route::delete('/{product}', 'destroy');
            });
    });
});
