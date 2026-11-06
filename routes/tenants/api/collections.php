<?php

declare(strict_types=1);

use App\Http\Controllers\API\v3\PaymentController;
use Illuminate\Support\Facades\Route;

Route::apiResource('payment', PaymentController::class)
->only(['store'])
->middleware(['feature:collections']);
