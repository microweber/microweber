<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Profile\Http\Controllers\Api\ProfileApiController;

/*
|--------------------------------------------------------------------------
| Profile Module API Routes
|--------------------------------------------------------------------------
|
| Migrated from routes/module-api.php (lines 132-147). Profile operates
| on the authenticated caller, not a collection — there is no `{id}`
| route param. Any authenticated user (admin or not) can read and
| update their own record, gated by Passport scopes.
|
*/

Route::prefix('api/module/profile')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:profile:read'])
    ->name('api.module.profile.')
    ->group(function () {
        Route::get('/', [ProfileApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/profile')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:profile:write'])
    ->name('api.module.profile.')
    ->group(function () {
        Route::put('/', [ProfileApiController::class, 'update'])->name('update');
        Route::patch('/', [ProfileApiController::class, 'update'])->name('update.partial');
        Route::post('/change-password', [ProfileApiController::class, 'changePassword'])->name('change-password');
    });
