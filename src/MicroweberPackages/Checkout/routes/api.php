<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/25/2020
 * Time: 11:13 AM
 */


Route::name('api.checkout.')
    ->prefix('api/checkout')
   // ->middleware(['web'])
    ->namespace('\MicroweberPackages\Checkout\Http\Controllers')
    ->group(function () {
        Route::post('validate', 'CheckoutController@validate');
    });