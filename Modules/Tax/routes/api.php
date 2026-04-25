<?php

use Illuminate\Support\Facades\Route;

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

\MicroweberPackages\Module\Routing\ModuleApiRoutes::register(
    'tax',
    \Modules\Tax\Http\Controllers\Api\TaxApiController::class,
    'tax'
);
