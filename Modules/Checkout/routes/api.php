<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Checkout\Http\Controllers\Api\CheckoutApiController;

/*
|--------------------------------------------------------------------------
| Checkout Module API Routes
|--------------------------------------------------------------------------
|
| Migrated from routes/module-api.php (lines 114-126). Checkout exposes
| action verbs (validate, shipping-methods, calculate-shipping, …) that
| don't fit the standard REST shape so it doesn't go through the loop.
| Session-backed, intentionally public.
|
*/

// task-2026-06-06-cartsession: checkout reads the cart via the session-scoped
// CartService::getCart(), so the route group needs the cookie+session stack.
// Without it the bare `api` group is stateless and every REST checkout sees an
// empty cart (the item was added under a different, non-persisted session id).
Route::prefix('api/module/checkout')
    ->middleware([
        'api',
        'throttle:public',
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
    ])
    ->name('api.module.checkout.')
    ->group(function () {
        Route::get('/', [CheckoutApiController::class, 'index'])->name('index');
        Route::post('/', [CheckoutApiController::class, 'store'])->name('store');
        Route::put('/', [CheckoutApiController::class, 'update'])->name('update');
        Route::post('/validate', [CheckoutApiController::class, 'validate'])->name('validate');
        Route::get('/shipping-methods', [CheckoutApiController::class, 'shippingMethods'])->name('shipping.methods');
        Route::get('/payment-methods', [CheckoutApiController::class, 'paymentMethods'])->name('payment.methods');
        Route::post('/calculate-shipping', [CheckoutApiController::class, 'calculateShipping'])->name('shipping.calculate');
        Route::get('/order/{orderReferenceId}', [CheckoutApiController::class, 'orderStatus'])->name('order.status');
    });
