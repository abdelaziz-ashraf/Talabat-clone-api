<?php

use App\Http\Controllers\Api\AddressController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerAuthController;
use App\Http\Controllers\Api\CustomerController;


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
