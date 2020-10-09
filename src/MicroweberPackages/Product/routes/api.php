<?php

Route::name('admin.')
    ->prefix('api')
    ->namespace('\MicroweberPackages\Product\Http\Controllers\Api')
    ->group(function () {
         Route::apiResource('products', 'ProductsApiController');
    });

