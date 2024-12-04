<?php

use App\Http\Controllers\Api\Customer\AuthController;
use App\Http\Controllers\Api\Customer\CustomerController;
use Illuminate\Support\Facades\Route;


Route::prefix('customers')->group(function () {
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('verifyCode', 'verifyCode');
    });

    Route::middleware(['auth:customer'])->group(function () {

        Route::prefix('profile')->controller(CustomerController::class)
            ->group(function () {
                Route::get('/my-account', 'show');
                Route::put('/', 'update');
                Route::delete('/', 'destroy');
            });

        Route::prefix('addresses')
            ->controller(\App\Http\Controllers\Api\Customer\AddressController::class)
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/active', 'customerActiveAddresses');
                Route::post('/', 'store');
                Route::put('/{address}', 'update');
                Route::delete('/{address}', 'destroy');
                Route::put('/{address}/toggle-active', 'toggleActive');
            });

        Route::prefix('vendors')
            ->controller(\App\Http\Controllers\Api\Customer\VendorController::class)
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/{vendor}', 'show');
                Route::get('/{vendor}/menu', 'menu');

                Route::controller(\App\Http\Controllers\Api\Customer\CategoryController::class)
                    ->group(function () {
                        Route::get('/{vendor}/categories', 'getVendorCategories');
                        Route::get('/categories/{category}', 'show');
                    });



            });

        Route::prefix('products')
            ->controller(\App\Http\Controllers\Api\Customer\ProductController::class)
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/{product}', 'show');
            });
    });
});
