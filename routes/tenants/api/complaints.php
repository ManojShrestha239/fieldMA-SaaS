<?php

declare(strict_types=1);

use App\Http\Controllers\API\v4\ComplaintController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['feature:complaints']], function () {
    Route::apiResource('complaint', ComplaintController::class, ['as' => 'api']);
    Route::get('issue_type', [ComplaintController::class, 'issue_type'], ['as' => 'api']);
});
