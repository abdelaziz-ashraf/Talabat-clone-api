<?php

use Illuminate\Support\Facades\Route;

Route::prefix('delivery')->group(function () {
    Route::prefix('auth')
        ->controller(\App\Http\Controllers\Api\Delivery\AuthController::class)
        ->group(function () {
            Route::post('register', 'register');
            Route::post('login', 'login');
            Route::post('verify-account', 'verifyAccount');
        });

});
