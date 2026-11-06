<?php

declare(strict_types=1);

use App\Http\Controllers\CartController;
use App\Http\Controllers\ChallanController;
use App\Http\Controllers\ChallanReportController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['feature:dealer_orders'])->group(function () {
    Route::resource('order', OrderController::class);
    Route::post('addstoreOrder', [OrderController::class, 'addOrderstore']);

    Route::get('productlist', [CartController::class, 'getProduct']);
    Route::get('sub-productlist', [CartController::class, 'getSubProduct']);
    Route::get('productsize', [CartController::class, 'getproductsize']);

    Route::get('addorder/{id}', [CartController::class, 'addOrder'])->name('addorder');
    Route::get('orderRequest', [CartController::class, 'orderRequest']);
    Route::post('add-cart-to-order', [CartController::class, 'addOrderstore'])->name('store-order');
    Route::post('store-to-cart', [CartController::class, 'storeToCart'])->name('store-to-cart');
    Route::delete('storetocart/{id}', [CartController::class, 'destroy'])->name('storetocart.destory');
    Route::patch('store-tocart/{id}', [CartController::class, 'update'])->name('storetocart.update');

    Route::post('orderCollectionReport', [OrderReportController::class, 'ordercollection']);
    Route::post('orderTrackingReport', [OrderReportController::class, 'orderreport']);
    Route::get('/allorder/{id}', [OrderController::class, 'allOrder']);

    Route::get('/order/edit/{id}', [OrderController::class, 'editOrderView']);
    Route::patch('/order/edit/{id}', [OrderController::class, 'chalan']);
    Route::get('/orders/{status}', [OrderController::class, 'status']);
    Route::get('/order/{id}', [OrderController::class, 'general']);
    Route::get('/orderpop/{id}', [OrderController::class, 'pop']);
    Route::get('orderdetails/{id}', [OrderController::class, 'orderdetails']);

    Route::get('/order/challan', [ChallanController::class, 'listChallan']);
    Route::get('/order/challan/{id}', [ChallanController::class, 'challan']);
    Route::get('/order/singleChallan/{id}', [ChallanController::class, 'singleChallan']);
    Route::get('/challan/edit/{id}', [ChallanController::class, 'challanEditView']);
    Route::PATCH('/challan/edit/{id}', [ChallanController::class, 'challanAdd']);
    Route::PATCH('/order/availability/{id}', [ChallanController::class, 'availability']);
    Route::POST('/order/singleChallan/{id}', [ChallanController::class, 'singleChallanAdd']);
    Route::POST('/order/challan/{id}', [ChallanController::class, 'allChallan']);

    Route::resource('/orderReport', OrderReportController::class);

    Route::get('/challan/report/{id}', [ChallanReportController::class, 'challanreport']);

    Route::post('/orederrequest', [CartController::class, 'addOrderRequest']);

    Route::get('viewcomplaint/{id}', [OrderController::class, 'viewcomplaint']);
});
