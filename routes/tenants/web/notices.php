<?php

declare(strict_types=1);

use App\Http\Controllers\NoticeController;
use App\Http\Controllers\NoticeImageController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['feature:notices']], function () {
    Route::post('/notice/search', [NoticeController::class, 'search']);
    Route::patch('/noticePush/{id}', [NoticeController::class, 'noticePush']);
    Route::resource('notice', NoticeController::class);
    Route::resource('noticefile', NoticeImageController::class)->only('destroy');
});
