<?php

use Illuminate\Support\Facades\Route;
use Modules\Post\Http\Controllers\Api\PostApiController;

/*
|--------------------------------------------------------------------------
| Post API Routes
|--------------------------------------------------------------------------
|
| These routes provide a RESTful API for post management with Sanctum
| authentication and rate limiting.
|
*/

// Public API routes (read-only)
Route::name('api.post.')
    ->prefix('api/posts')
    ->middleware(['api'])
    ->group(function () {
        Route::get('/', [PostApiController::class, 'index'])->name('index');
        Route::get('/{post}', [PostApiController::class, 'show'])->name('show');
    });

// Protected API routes (full CRUD)
Route::name('api.post.')
    ->prefix('api/posts')
    ->middleware(['api', 'auth:api', 'throttle:api'])
    ->group(function () {
        Route::post('/', [PostApiController::class, 'store'])->name('store');
        Route::put('/{post}', [PostApiController::class, 'update'])->name('update');
        Route::patch('/{post}', [PostApiController::class, 'update'])->name('update.partial');
        Route::delete('/{post}', [PostApiController::class, 'destroy'])->name('destroy');
    });

// Legacy admin routes (backward compatibility)
Route::name('api.')
    ->prefix('api')
    ->middleware(['api', 'admin'])
    ->group(function () {
        Route::apiResource('post', PostApiController::class);
    });

/*
|--------------------------------------------------------------------------
| Headless Module API (posts)
|--------------------------------------------------------------------------
|
| Migrated from the global routes/module-api.php  loop. Reads
| are public (rate-limited); writes require a Passport admin-scoped
| token. Same controller as the legacy /api/posts/* surface above so
| both clients keep working through one implementation.
|
*/

\MicroweberPackages\Module\Routing\ModuleApiRoutes::register(
    'posts',
    \Modules\Post\Http\Controllers\Api\PostApiController::class,
    'post'
);
