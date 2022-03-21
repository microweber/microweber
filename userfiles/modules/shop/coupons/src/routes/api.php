<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

\Route::name('api.')

    ->prefix('api')
    ->middleware(['api', 'xss', \Illuminate\Routing\Middleware\ThrottleRequests::class])
 //   ->namespace('MicroweberPackages\Modules\Shop\Coupons')
    ->group(function () {

        Route::post('coupon_apply', function () {
            $data = request()->all();
            return coupon_apply($data);
        })->name('coupon_apply');

        Route::post('coupons_delete_session', function () {
            $data = request()->all();
            return coupons_delete_session($data);
        })->name('coupons_delete_session');

    });

\Route::name('api.')

    ->prefix('api')
    ->middleware(['api', 'xss', 'admin', \Illuminate\Routing\Middleware\ThrottleRequests::class])
    //   ->namespace('MicroweberPackages\Modules\Shop\Coupons')
    ->group(function () {

        Route::post('coupons_save_coupon', function () {

            $request = request();
            $rules = [];
            $rules['coupon_name'] = 'max:500';
            $rules['coupon_code'] = 'max:500';
            $rules['uses_per_coupon'] = 'max:500';
            $rules['uses_per_customer'] = 'max:500';
            $rules['discount_type'] = 'max:500';
            $rules['discount_value'] = 'max:500';
            $rules['total_amount'] = 'max:500';

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $response = \Response::make(['errors' => $validator->messages()->toArray()]);
                $response->setStatusCode(422);
                $response = \MicroweberPackages\App\Http\RequestRoute::formatFrontendResponse($response);

                return $response;
            }

            $data = $request->all();
            return coupons_save_coupon($data);
        })->name('coupons_save_coupon');

        Route::post('coupon_delete', function () {
            $data = request()->all();
            return coupon_delete($data);
        })->name('coupon_delete');

    });
