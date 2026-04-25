<?php
use  \Illuminate\Support\Facades\Route;

Route::name('api.')
    ->prefix('api')
    ->middleware(['api','admin'])
    ->group(function () {
        Route::apiResource('order', \Modules\Order\Http\Controllers\Api\OrderApiController::class);
    });

/*
|--------------------------------------------------------------------------
| Headless Module API (orders)
|--------------------------------------------------------------------------
|
| Migrated from the global routes/module-api.php  loop. Reads
| are public (rate-limited); writes require a Passport admin-scoped
| token. Same controller as the legacy /api/orders/* surface above so
| both clients keep working through one implementation.
|
*/

\MicroweberPackages\Module\Routing\ModuleApiRoutes::register(
    'orders',
    \Modules\Order\Http\Controllers\Api\OrdersApiController::class,
    'order'
);
