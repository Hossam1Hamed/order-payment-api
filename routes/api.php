<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    // Auth
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Orders
    Route::apiResource('orders', OrderController::class);

    // Payments
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/orders/{order}/payments', [PaymentController::class, 'store']);
    Route::get('/orders/{order}/payments', [PaymentController::class, 'orderPayments']);
});