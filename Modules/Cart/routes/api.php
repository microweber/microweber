<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\Api\CartApiController;

/*
|--------------------------------------------------------------------------
| Cart Module API Routes
|--------------------------------------------------------------------------
|
| Migrated from routes/module-api.php (lines 100-112). Cart exposes
| action verbs (empty, totals, apply-coupon) that don't fit the
| standard REST shape so it doesn't go through the loop. The cart is
| session-backed and intentionally public — no admin guard.
|
*/

Route::prefix('api/module/cart')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.cart.')
    ->group(function () {
        Route::get('/', [CartApiController::class, 'index'])->name('index');
        Route::post('/', [CartApiController::class, 'store'])->name('store');
        Route::get('/totals', [CartApiController::class, 'totals'])->name('totals');
        Route::delete('/empty', [CartApiController::class, 'empty'])->name('empty');
        Route::post('/coupon', [CartApiController::class, 'applyCoupon'])->name('coupon.apply');
        Route::delete('/coupon', [CartApiController::class, 'removeCoupon'])->name('coupon.remove');
        Route::put('/{id}', [CartApiController::class, 'update'])->name('update');
        Route::delete('/{id}', [CartApiController::class, 'destroy'])->name('destroy');
    });
