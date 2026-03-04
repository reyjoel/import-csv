<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::middleware('throttle:60,1')->group(function () {
    Route::apiResource('customers', CustomerController::class);
});