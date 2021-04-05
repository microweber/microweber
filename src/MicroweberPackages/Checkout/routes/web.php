<?php

Route::name('checkout.')
    ->prefix('checkout')
    ->namespace('\MicroweberPackages\Checkout\Http\Controllers')
    ->group(function () {
        Route::get('cart', 'CheckoutController@index')->name('cart');
        Route::get('contact-information', 'CheckoutController@contactInformation')->name('contact_information');
        Route::post('contact-information-save', 'CheckoutController@contactInformationSave')->name('contact_information_save');
        Route::get('shipping-method', 'CheckoutController@shippingMethod')->name('shipping_method');
        Route::post('shipping-method-save', 'CheckoutController@shippingMethodSave')->name('shipping_method_save');
        Route::get('payment-method', 'CheckoutController@paymentMethod')->name('payment_method');
        Route::post('payment-method-save', 'CheckoutController@paymentMethodSave')->name('payment_method_save');
        Route::get('finish', 'CheckoutController@finish')->name('finish');
    });
