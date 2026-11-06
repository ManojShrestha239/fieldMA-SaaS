<?php

declare(strict_types=1);

use App\Http\Controllers\API\v3\MessageController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['feature:chats']], function () {
    Route::get('/userlist', [MessageController::class, 'userlist']);
    Route::apiResource('message', MessageController::class)->only(['index', 'store']);
});
