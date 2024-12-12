<?php

use Illuminate\Support\Facades\Route;
use Modules\Checkout\Http\Controllers\CheckoutPaymentController;


Route::group(['middleware' => 'api'], function () {

    Route::get('api/checkout/payment/return', [CheckoutPaymentController::class, 'return'])->name('checkout.payment.return'); // checkout.payment.return
    Route::get('api/checkout/payment/cancel', [CheckoutPaymentController::class, 'cancel'])->name('checkout.payment.cancel');// checkout.payment.cancel
    Route::post('api/checkout/payment/notify', [CheckoutPaymentController::class, 'notify'])->name('checkout.payment.notify'); // checkout.payment.notify

});

Route::group(['middleware' => 'web'], function () {

    Route::any('api/shop/redirect_to_checkout', function () {
        return app()->shop_manager->redirect_to_checkout();
    });
});
