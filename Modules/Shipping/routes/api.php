<?php

use  \Illuminate\Support\Facades\Route;
use Modules\Shipping\Http\Controllers\Api\ShippingApiController;





/*
|--------------------------------------------------------------------------
| Headless Module API (shipping)
|--------------------------------------------------------------------------
|
| Migrated from the global routes/module-api.php  loop. Reads
| are public (rate-limited); writes require a Passport admin-scoped
| token. Same controller as the legacy /api/shipping/* surface above so
| both clients keep working through one implementation.
|
*/

Route::prefix('api/module/shipping')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.shipping.')
    ->group(function () {
        Route::get('/', [ShippingApiController::class, 'index'])->name('index');
        Route::get('/{shipping}', [ShippingApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/shipping')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:shipping:write'])
    ->name('api.module.shipping.')
    ->group(function () {
        Route::post('/', [ShippingApiController::class, 'store'])->name('store');
        Route::put('/{shipping}', [ShippingApiController::class, 'update'])->name('update');
        Route::patch('/{shipping}', [ShippingApiController::class, 'update'])->name('update.partial');
        Route::delete('/{shipping}', [ShippingApiController::class, 'destroy'])->name('destroy');
    });

