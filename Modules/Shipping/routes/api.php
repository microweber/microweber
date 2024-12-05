<?php

use  \Illuminate\Support\Facades\Route;

Route::name('shop.shipping.')
    ->prefix('api/shop/shipping')
    ->middleware(['xss'])
     ->group(function () {
        Route::match(array('GET', 'POST'), 'set_provider',
            \Modules\Shipping\Http\Controllers\Api::class.'@setShippingProviderToSession')->name('set_provider');
    });




