<?php

Route::name('api.')
    ->prefix('api')
    ->middleware(['api','admin'])
    ->namespace('\MicroweberPackages\Order\Http\Controllers\Api')
    ->group(function () {
        Route::apiResource('order', 'OrderApiController');
    });