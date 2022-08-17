<?php

Route::name('api.')
    ->prefix('api')
    ->middleware([
        \MicroweberPackages\App\Http\Middleware\SameSiteRefererMiddleware::class
    ])
    ->group(function () {

        Route::post('update_cart', function (\Illuminate\Http\Request $request) {
            return update_cart($request->all());
        });

        Route::post('remove_cart_item', function (\Illuminate\Http\Request $request) {
            return remove_cart_item($request->all());
        });

        Route::post('update_cart_item_qty', function (\Illuminate\Http\Request $request) {
            return update_cart_item_qty($request->all());
        });

    });
