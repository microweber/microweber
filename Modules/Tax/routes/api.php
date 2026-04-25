<?php

use Illuminate\Support\Facades\Route;
use Modules\Tax\Http\Controllers\Api\TaxApiController;

Route::name('api.')

    ->prefix('api')
    ->middleware(['api', 'admin', 'xss'])
    ->namespace('\MicroweberPackages\Tax\Http\Controllers\Api')
    ->group(function () {

        Route::any('shop/save_tax_item', function () {
            $data = request()->all();
            return app()->tax_manager->save($data);
        });

        Route::any('shop/delete_tax_item', function () {
            $data = request()->all();
            return app()->tax_manager->delete_by_id($data);
        });

    });

/*
|--------------------------------------------------------------------------
| Headless Module API (tax)
|--------------------------------------------------------------------------
|
| Migrated from the global routes/module-api.php  loop. Reads
| are public (rate-limited); writes require a Passport admin-scoped
| token. Same controller as the legacy /api/tax/* surface above so
| both clients keep working through one implementation.
|
*/

Route::prefix('api/module/tax')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.tax.')
    ->group(function () {
        Route::get('/', [TaxApiController::class, 'index'])->name('index');
        Route::get('/{tax}', [TaxApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/tax')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:tax:write'])
    ->name('api.module.tax.')
    ->group(function () {
        Route::post('/', [TaxApiController::class, 'store'])->name('store');
        Route::put('/{tax}', [TaxApiController::class, 'update'])->name('update');
        Route::patch('/{tax}', [TaxApiController::class, 'update'])->name('update.partial');
        Route::delete('/{tax}', [TaxApiController::class, 'destroy'])->name('destroy');
    });

