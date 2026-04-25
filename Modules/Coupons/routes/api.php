<?php

use Illuminate\Support\Facades\Route;
use Modules\Coupons\Http\Controllers\Api\CouponController;

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

\MicroweberPackages\Module\Routing\ModuleApiRoutes::register(
    'coupons',
    \Modules\Coupons\Http\Controllers\Api\CouponsApiController::class,
    'coupon'
);
