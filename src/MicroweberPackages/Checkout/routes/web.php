<?php

// Private
Route::name('checkout.')
   // ->prefix(multilanguage_route_prefix('checkout'))
    ->middleware([
        \MicroweberPackages\App\Http\Middleware\VerifyCsrfToken::class,
       \MicroweberPackages\Checkout\Http\Middleware\CheckoutV2::class,
       \MicroweberPackages\App\Http\Middleware\XSS::class
   ])
    ->namespace('\MicroweberPackages\Checkout\Http\Controllers')
    ->group(function () {

        // Contact info
        Route::get('cart', 'CheckoutController@index')->name('cart');
        Route::get('contact-information', 'CheckoutController@contactInformation')->name('contact_information');
        Route::post('contact-information-save', 'CheckoutController@contactInformationSave')->name('contact_information_save');

        // Shipping info
        Route::get('shipping-method', 'CheckoutController@shippingMethod')->name('shipping_method');
        Route::post('shipping-method-change', 'CheckoutController@shippingMethodChange')->name('shipping_method_change');
        Route::post('shipping-method-save', 'CheckoutController@shippingMethodSave')->name('shipping_method_save');

        // Payment method
        Route::get('payment-method', 'CheckoutController@paymentMethod')->name('payment_method');
        Route::post('payment-method-change', 'CheckoutController@paymentMethodChange')->name('payment_method_change');
        Route::post('payment-method-save', 'CheckoutController@paymentMethodSave')->name('payment_method_save');
    });

// Public
Route::name('checkout.')
    //->prefix(multilanguage_route_prefix('checkout'))
    ->prefix('checkout')
    ->namespace('\MicroweberPackages\Checkout\Http\Controllers')
    ->group(function () {
        Route::get('login', 'CheckoutController@login')->name('login');
        Route::get('forgot-password', 'CheckoutController@forgotPassword')->name('forgot_password');
        Route::get('register', 'CheckoutController@register')->name('register');
        Route::get('finish/{id}', 'CheckoutController@finish')->name('finish');
    });
