<?php

Route::name('api.')
    ->prefix('api')
    ->middleware(['api','admin'])
    ->namespace('\MicroweberPackages\Product\Http\Controllers\Api')
    ->group(function () {
        Route::apiResource('product', 'ProductApiController');
        Route::apiResource('product_variant', 'ProductVariantApiController');


        Route::post('product_variant_save', function() {

            $options = request()->post('options', []);
            if (!empty($options)) {




                // $getProduct = \MicroweberPackages\Product\Models\Product::where('id', 15)->first();
                // $getProduct->generateVariants();

            }

        });

    });
