<?php

use Illuminate\Support\Facades\Route;
use Modules\Coupons\Http\Controllers\Api\CouponController;
use Modules\Coupons\Http\Controllers\Api\CouponsApiController;

/*
|--------------------------------------------------------------------------
| Headless Module API (coupons)
|--------------------------------------------------------------------------
|
| Migrated from the global routes/module-api.php  loop. Reads
| are public (rate-limited); writes require a Passport admin-scoped
| token. Same controller as the legacy /api/coupons/* surface above so
| both clients keep working through one implementation.
|
*/

Route::prefix('api/module/coupons')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.coupons.')
    ->group(function () {
        Route::get('/', [CouponsApiController::class, 'index'])->name('index');
        Route::get('/{coupon}', [CouponsApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/coupons')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:coupons:write'])
    ->name('api.module.coupons.')
    ->group(function () {
        Route::post('/', [CouponsApiController::class, 'store'])->name('store');
        Route::put('/{coupon}', [CouponsApiController::class, 'update'])->name('update');
        Route::patch('/{coupon}', [CouponsApiController::class, 'update'])->name('update.partial');
        Route::delete('/{coupon}', [CouponsApiController::class, 'destroy'])->name('destroy');
    });

