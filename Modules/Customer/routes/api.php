<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Api\CustomersApiController;

/*
|--------------------------------------------------------------------------
| Customer Module API Routes
|--------------------------------------------------------------------------
|
| Migrated from routes/module-api.php's global $modules loop.
| Reads are public (rate-limited); writes require a Passport
| admin-scoped token.
|
*/

Route::prefix('api/module/customers')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.customers.')
    ->group(function () {
        Route::get('/', [CustomersApiController::class, 'index'])->name('index');
        Route::get('/{customer}', [CustomersApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/customers')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:customers:write'])
    ->name('api.module.customers.')
    ->group(function () {
        Route::post('/', [CustomersApiController::class, 'store'])->name('store');
        Route::put('/{customer}', [CustomersApiController::class, 'update'])->name('update');
        Route::patch('/{customer}', [CustomersApiController::class, 'update'])->name('update.partial');
        Route::delete('/{customer}', [CustomersApiController::class, 'destroy'])->name('destroy');
    });
