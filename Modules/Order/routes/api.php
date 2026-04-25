<?php
use  \Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Api\OrdersApiController;

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

Route::prefix('api/module/orders')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.orders.')
    ->group(function () {
        Route::get('/', [OrdersApiController::class, 'index'])->name('index');
        Route::get('/{order}', [OrdersApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/orders')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:orders:write'])
    ->name('api.module.orders.')
    ->group(function () {
        Route::post('/', [OrdersApiController::class, 'store'])->name('store');
        Route::put('/{order}', [OrdersApiController::class, 'update'])->name('update');
        Route::patch('/{order}', [OrdersApiController::class, 'update'])->name('update.partial');
        Route::delete('/{order}', [OrdersApiController::class, 'destroy'])->name('destroy');
    });

