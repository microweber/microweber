<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\Api\SettingsApiController;

/*
|--------------------------------------------------------------------------
| Settings Module API Routes
|--------------------------------------------------------------------------
|
| Migrated from routes/module-api.php (lines 179-195) so the module owns
| its own /api/module/settings/* surface. Reads are public for a
| whitelist of safe option keys; writes require an admin-scoped
| Passport token.
|
| Using `{key}` rather than `{id}` because option keys are stable
| identifiers whereas ids are not.
|
*/

Route::prefix('api/module/settings')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.settings.')
    ->group(function () {
        Route::get('/', [SettingsApiController::class, 'index'])->name('index');
        Route::get('/{key}', [SettingsApiController::class, 'show'])
            ->where('key', '[A-Za-z0-9_\-.]+')
            ->name('show');
    });

Route::prefix('api/module/settings')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:settings:write'])
    ->name('api.module.settings.')
    ->group(function () {
        Route::post('/', [SettingsApiController::class, 'store'])->name('store');
        Route::put('/{key}', [SettingsApiController::class, 'update'])
            ->where('key', '[A-Za-z0-9_\-.]+')
            ->name('update');
        Route::patch('/{key}', [SettingsApiController::class, 'update'])
            ->where('key', '[A-Za-z0-9_\-.]+')
            ->name('update.partial');
        Route::delete('/{key}', [SettingsApiController::class, 'destroy'])
            ->where('key', '[A-Za-z0-9_\-.]+')
            ->name('destroy');
    });
