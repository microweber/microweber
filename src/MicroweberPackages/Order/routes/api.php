<?php

Route::name('api.')
    ->prefix('api')
    ->middleware(['api'])
    ->namespace('\MicroweberPackages\Order\Http\Controllers\Api')
    ->group(function () {
        Route::apiResource('order', 'OrderApiController');
    });