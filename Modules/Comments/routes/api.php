<?php

use Illuminate\Support\Facades\Route;
use Modules\Comments\Http\Controllers\Api\CommentsApiController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/


/*
|--------------------------------------------------------------------------
| Headless Module API (comments)
|--------------------------------------------------------------------------
|
| Migrated from the global routes/module-api.php  loop. Reads
| are public (rate-limited); writes require a Passport admin-scoped
| token. Same controller as the legacy /api/comments/* surface above so
| both clients keep working through one implementation.
|
*/

Route::prefix('api/module/comments')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.comments.')
    ->group(function () {
        Route::get('/', [CommentsApiController::class, 'index'])->name('index');
        Route::get('/{comment}', [CommentsApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/comments')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:comments:write'])
    ->name('api.module.comments.')
    ->group(function () {
        Route::post('/', [CommentsApiController::class, 'store'])->name('store');
        Route::put('/{comment}', [CommentsApiController::class, 'update'])->name('update');
        Route::patch('/{comment}', [CommentsApiController::class, 'update'])->name('update.partial');
        Route::delete('/{comment}', [CommentsApiController::class, 'destroy'])->name('destroy');
    });

