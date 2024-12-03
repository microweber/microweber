<?php

use Illuminate\Support\Facades\Route;
use Modules\Checkout\Http\Controllers\CheckoutController;

// Private
Route::name('checkout.')
    ->prefix('checkout')
    // ->prefix(multilanguage_route_prefix('checkout'))
    ->middleware([
        \Modules\Checkout\Http\Middleware\CheckoutEmptyCart::class,
        \MicroweberPackages\App\Http\Middleware\XSS::class
    ])
    ->group(function () {

        // Contact info
        Route::get('cart', CheckoutController::class . '@index')->name('cart');
        Route::get('contact-information', CheckoutController::class . '@contactInformation')->name('contact_information');
        Route::post('contact-information-save', CheckoutController::class . '@contactInformationSave')->name('contact_information_save');

        // Shipping info
        Route::get('shipping-method', CheckoutController::class . '@shippingMethod')->name('shipping_method');
        Route::post('shipping-method-change', CheckoutController::class . '@shippingMethodChange')->name('shipping_method_change');
        Route::post('shipping-method-save', CheckoutController::class . '@shippingMethodSave')->name('shipping_method_save');

        // Payment method
        Route::get('payment-method', CheckoutController::class . '@paymentMethod')->name('payment_method');
        Route::post('payment-method-change', CheckoutController::class . '@paymentMethodChange')->name('payment_method_change');
        Route::post('payment-method-save', CheckoutController::class . '@paymentMethodSave')->name('payment_method_save');
    });

// Public
Route::name('checkout.')
    //->prefix(multilanguage_route_prefix('checkout'))
    ->prefix('checkout')
    ->group(function () {
        Route::get('login', CheckoutController::class.'@login')->name('login');
        Route::get('forgot-password', CheckoutController::class.'@forgotPassword')->name('forgot_password');
        Route::get('register', CheckoutController::class.'@register')->name('register');
        Route::get('finish/{id}', CheckoutController::class.'@finish')->name('finish');
    });
