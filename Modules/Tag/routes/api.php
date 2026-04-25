<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Tag\Http\Controllers\Api\TagApiController;

/*
|--------------------------------------------------------------------------
| Tag Module API Routes
|--------------------------------------------------------------------------
|
| Migrated from routes/module-api.php's global $modules loop. Reads are
| public (rate-limited); writes require a Passport admin-scoped token.
|
*/

Route::prefix('api/module/tags')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.tags.')
    ->group(function () {
        Route::get('/', [TagApiController::class, 'index'])->name('index');
        Route::get('/{tag}', [TagApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/tags')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:tags:write'])
    ->name('api.module.tags.')
    ->group(function () {
        Route::post('/', [TagApiController::class, 'store'])->name('store');
        Route::put('/{tag}', [TagApiController::class, 'update'])->name('update');
        Route::patch('/{tag}', [TagApiController::class, 'update'])->name('update.partial');
        Route::delete('/{tag}', [TagApiController::class, 'destroy'])->name('destroy');
    });
