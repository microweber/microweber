<?php

Route::name('admin.')
    ->prefix('api')
    ->namespace('\MicroweberPackages\Product\Http\Controllers\Api')
    ->group(function () {
<<<<<<< HEAD
         Route::apiResource('products', 'ProductsApiController');
=======
         Route::apiResource('products', 'ProductsController');
>>>>>>> d05ca5b1507c4e5a230b0587fe65767e562ff629
    });

