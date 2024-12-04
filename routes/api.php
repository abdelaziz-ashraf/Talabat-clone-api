<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return \App\Http\Responses\SuccessResponse::send('Talabat API Clone .. ');
});
