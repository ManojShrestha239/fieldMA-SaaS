<?php

declare(strict_types=1);

use App\Http\Controllers\ProductManufactureController;
use App\Http\Controllers\RawInventoryController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\RawProductController;
use App\Http\Controllers\RawPurchaseController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:rawmaterial', 'feature:inventory']], function () {
    //begin for raw materials
    Route::resource('raw', RawMaterialController::class);
    //for raw-products
    Route::resource('raw-product', RawProductController::class);
    //rawpurchase
    Route::resource('rawpurchase', RawPurchaseController::class);
    //productmanufactures
    Route::resource('product-manufactures', ProductManufactureController::class);
    //inventory
    Route::resource('inventory', RawInventoryController::class);
});
