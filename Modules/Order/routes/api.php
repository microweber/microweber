<?php
use  \Illuminate\Support\Facades\Route;

Route::name('api.')
    ->prefix('api')
    ->middleware(['api','admin'])
    ->group(function () {
        Route::apiResource('order', \Modules\Order\Http\Controllers\Api\OrderApiController::class);
    });
