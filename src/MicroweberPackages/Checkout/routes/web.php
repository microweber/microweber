<?php

Route::name('checkout.')
    ->prefix('checkout')
    ->namespace('\MicroweberPackages\Checkout\Http\Controllers')
    ->group(function () {
        Route::get('cart', 'CheckoutController@index')->name('cart');
        Route::get('contact_information', 'CheckoutController@contactInformation')->name('contact_information');
    });
