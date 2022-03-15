<?php
use Illuminate\Support\Facades\Route;

\Route::name('api.')

    ->prefix('api')
    ->middleware(['api', 'xss', \Illuminate\Routing\Middleware\ThrottleRequests::class])
 //   ->namespace('MicroweberPackages\Modules\Shop\Coupons')
    ->group(function () {

        Route::post('coupon_apply', function () {
            $data = request()->all();
            return coupon_apply($data);
        });
        Route::post('coupons_delete_session', function () {
            $data = request()->all();
            return coupons_delete_session($data);
        });

    });

\Route::name('api.')

    ->prefix('api')
    ->middleware(['api', 'xss', 'admin', \Illuminate\Routing\Middleware\ThrottleRequests::class])
    //   ->namespace('MicroweberPackages\Modules\Shop\Coupons')
    ->group(function () {

        Route::post('coupons_save_coupon', function () {
            $data = request()->all();
            return coupons_save_coupon($data);
        });

        Route::post('coupon_delete', function () {
            $data = request()->all();
            return coupon_delete($data);
        });

    });
