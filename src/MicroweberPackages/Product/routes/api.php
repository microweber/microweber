<?php

Route::name('api.')
    ->prefix('api')
    ->namespace('\MicroweberPackages\Product\Http\Controllers\Api')
    ->group(function () {
        Route::apiResource('product', 'ProductApiController');
    });

