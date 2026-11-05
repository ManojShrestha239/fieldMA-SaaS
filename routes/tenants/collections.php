<?php

declare(strict_types=1);

use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CollectionReportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:collection', 'feature:collections']], function () {
    Route::get('/collection', [PaymentController::class, 'list']);
    Route::post('/collection', [PaymentController::class, 'filter']);
    Route::get('/cheque-collection', [PaymentController::class, 'cheque_list']);
    Route::post('/cheque-collection', [PaymentController::class, 'cheque_filter']);
    Route::get('/direct-collection', [PaymentController::class, 'direct_list']);
    Route::post('/direct-collection', [PaymentController::class, 'direct_filter']);
    Route::patch('/cheque-collection/{id}', [PaymentController::class, 'update']);
    Route::get('/collectionpop/{id}', [PaymentController::class, 'pop']);

    Route::get('/token/details/{id}', [TokenController::class, 'details']);
    Route::resource('token', TokenController::class);

    Route::post('zeroReport', [CollectionReportController::class, 'zeroReport']);
    Route::resource('collection-report', CollectionController::class)->only(['index', 'store']);
    Route::get('/collection-report/filter', [CollectionController::class, 'filter']);

    Route::resource('/collectionReport', CollectionReportController::class);

    Route::post('//collectionSummaryReport', [CollectionReportController::class, 'collectionSummaryReport']);
    Route::patch('/edit-collection/{id}', [PaymentController::class, 'edit']);

    Route::get('/cashPopup/{id}', [PaymentController::class, 'cashPop']);
    Route::get('receiptimage/{id}', [CollectionController::class, 'show'])->name('collection.receiptimage');
});
