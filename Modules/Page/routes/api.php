<?php

use Illuminate\Support\Facades\Route;
use Modules\Page\Http\Controllers\Api\PageApiController;

/*
|--------------------------------------------------------------------------
| Page API Routes
|--------------------------------------------------------------------------
|
| These routes provide a RESTful API for page management with Sanctum
| authentication and rate limiting.
|
*/

// Public API routes (read-only)
Route::name('api.page.')
    ->prefix('api/pages')
    ->middleware(['api'])
    ->group(function () {
        Route::get('/', [PageApiController::class, 'index'])->name('index');
        Route::get('/{page}', [PageApiController::class, 'show'])->name('show');
    });

// Protected API routes (full CRUD)
Route::name('api.page.')
    ->prefix('api/pages')
    ->middleware(['api', 'auth:api', 'throttle:api'])
    ->group(function () {
        Route::post('/', [PageApiController::class, 'store'])->name('store');
        Route::put('/{page}', [PageApiController::class, 'update'])->name('update');
        Route::patch('/{page}', [PageApiController::class, 'update'])->name('update.partial');
        Route::delete('/{page}', [PageApiController::class, 'destroy'])->name('destroy');
    });

// Legacy admin routes (backward compatibility)
Route::name('api.')
    ->prefix('api')
    ->middleware(['api', 'admin'])
    ->group(function () {
        Route::apiResource('page', PageApiController::class);
    });

/*
|--------------------------------------------------------------------------
| Headless Module API (pages)
|--------------------------------------------------------------------------
|
| Migrated from the global routes/module-api.php  loop. Reads
| are public (rate-limited); writes require a Passport admin-scoped
| token. Same controller as the legacy /api/pages/* surface above so
| both clients keep working through one implementation.
|
*/

Route::prefix('api/module/pages')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.pages.')
    ->group(function () {
        Route::get('/', [PageApiController::class, 'index'])->name('index');
        Route::get('/{page}', [PageApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/pages')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:pages:write'])
    ->name('api.module.pages.')
    ->group(function () {
        Route::post('/', [PageApiController::class, 'store'])->name('store');
        Route::put('/{page}', [PageApiController::class, 'update'])->name('update');
        Route::patch('/{page}', [PageApiController::class, 'update'])->name('update.partial');
        Route::delete('/{page}', [PageApiController::class, 'destroy'])->name('destroy');
    });

