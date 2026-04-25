<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use MicroweberPackages\User\Http\Controllers\Api\UsersApiController;

/*
|--------------------------------------------------------------------------
| Headless Module API Routes (residual)
|--------------------------------------------------------------------------
|
| Per-module REST blocks have been migrated into each module's own
| `routes/api.php`, loaded by the module's service provider via
| `$this->loadRoutesFrom(...)`. Each migrated block uses standard
| Laravel route declarations — see any `Modules/<X>/routes/api.php`
| for the canonical shape.
|
| Adding a new module here is a code smell — add it to the new
| module's `routes/api.php` instead.
|
| What's left here:
|   * `users` — the User package
|     (`MicroweberPackages\User`) is not a Module, so its
|     `/api/module/users/*` routes stay in the global file. When
|     the package gains its own routes loader this block can move
|     too.
|
*/

Route::prefix('api/module/users')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.users.')
    ->group(function () {
        Route::get('/', [UsersApiController::class, 'index'])->name('index');
        Route::get('/{user}', [UsersApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/users')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:users:write'])
    ->name('api.module.users.')
    ->group(function () {
        Route::post('/', [UsersApiController::class, 'store'])->name('store');
        Route::put('/{user}', [UsersApiController::class, 'update'])->name('update');
        Route::patch('/{user}', [UsersApiController::class, 'update'])->name('update.partial');
        Route::delete('/{user}', [UsersApiController::class, 'destroy'])->name('destroy');
    });
