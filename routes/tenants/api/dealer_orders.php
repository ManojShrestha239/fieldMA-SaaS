<?php

declare(strict_types=1);

use App\Http\Controllers\API\v3\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['feature:dealer_orders']], function () {
    Route::post('/order', [OrderController::class, 'store']);
    Route::get('/orderHistory', [OrderController::class, 'index']);
    Route::get('/orderDetails/{id}', [OrderController::class, 'show']);
});
