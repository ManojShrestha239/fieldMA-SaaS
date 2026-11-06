<?php

declare(strict_types=1);

use App\Http\Controllers\API\v2\NoticeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['feature:notices']], function () {
    Route::get('/notice', [NoticeController::class, 'index']);
    Route::get('/notice/{id}', [NoticeController::class, 'individual']);
});
