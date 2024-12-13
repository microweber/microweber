<?php

use Illuminate\Support\Facades\Route;
use Modules\Coupons\Http\Controllers\Api\CouponController;

Route::prefix('v2')->group(function () {
    Route::prefix('shop/coupons')->group(function () {
        Route::post('apply', [CouponController::class, 'apply'])->name('api.coupon.apply');
        Route::post('delete', [CouponController::class, 'delete'])->name('api.coupon.delete');
        Route::post('save', [CouponController::class, 'save'])->name('api.coupon.save');
        Route::post('delete-session', [CouponController::class, 'deleteSession'])->name('api.coupon.delete_session');
    });
});
