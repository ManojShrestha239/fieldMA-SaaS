<?php

declare(strict_types=1);

use App\Http\Controllers\API\v2\MobileuserController;
use Illuminate\Support\Facades\Route;

Route::get('/visitSchedule', [MobileuserController::class, 'visitSchedule'])->middleware(['feature:schedules']);
