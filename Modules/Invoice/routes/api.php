<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Invoice\Http\Controllers\Api\InvoicesApiController;

/*
|--------------------------------------------------------------------------
| Invoice Module API Routes
|--------------------------------------------------------------------------
|
| Migrated from routes/module-api.php's global $modules loop.
| Reads are public (rate-limited); writes require a Passport
| admin-scoped token.
|
*/

Route::prefix('api/module/invoices')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.invoices.')
    ->group(function () {
        Route::get('/', [InvoicesApiController::class, 'index'])->name('index');
        Route::get('/{invoice}', [InvoicesApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/invoices')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:invoices:write'])
    ->name('api.module.invoices.')
    ->group(function () {
        Route::post('/', [InvoicesApiController::class, 'store'])->name('store');
        Route::put('/{invoice}', [InvoicesApiController::class, 'update'])->name('update');
        Route::patch('/{invoice}', [InvoicesApiController::class, 'update'])->name('update.partial');
        Route::delete('/{invoice}', [InvoicesApiController::class, 'destroy'])->name('destroy');
    });
