<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ContactForm\Http\Controllers\Api\FormsApiController;

/*
|--------------------------------------------------------------------------
| ContactForm (Forms) Module API Routes
|--------------------------------------------------------------------------
|
| Migrated from routes/module-api.php's global $modules loop. `forms`
| and `contact-form` are aliases onto the same Form resource so clients
| can use either the legacy slug (`contact-form`) or the plural
| (`forms`); both reuse the canonical `forms:write` Passport scope.
|
*/

// Forms — public reads, admin writes.
Route::prefix('api/module/forms')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.forms.')
    ->group(function () {
        Route::get('/', [FormsApiController::class, 'index'])->name('index');
        Route::get('/{form}', [FormsApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/forms')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:forms:write'])
    ->name('api.module.forms.')
    ->group(function () {
        Route::post('/', [FormsApiController::class, 'store'])->name('store');
        Route::put('/{form}', [FormsApiController::class, 'update'])->name('update');
        Route::patch('/{form}', [FormsApiController::class, 'update'])->name('update.partial');
        Route::delete('/{form}', [FormsApiController::class, 'destroy'])->name('destroy');
    });

// Legacy alias: `contact-form` reuses the same controller and the
// same `forms:write` scope so existing clients keep working.
Route::prefix('api/module/contact-form')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.contact-form.')
    ->group(function () {
        Route::get('/', [FormsApiController::class, 'index'])->name('index');
        Route::get('/{form}', [FormsApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/contact-form')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:forms:write'])
    ->name('api.module.contact-form.')
    ->group(function () {
        Route::post('/', [FormsApiController::class, 'store'])->name('store');
        Route::put('/{form}', [FormsApiController::class, 'update'])->name('update');
        Route::patch('/{form}', [FormsApiController::class, 'update'])->name('update.partial');
        Route::delete('/{form}', [FormsApiController::class, 'destroy'])->name('destroy');
    });
