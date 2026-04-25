<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Newsletter\Http\Controllers\Api\NewsletterApiController;

/*
|--------------------------------------------------------------------------
| Newsletter Module API Routes
|--------------------------------------------------------------------------
|
| Migrated from routes/module-api.php (lines 155-172). Subscribing is
| public (self-subscribe by email); listing, show, update, and destroy
| are admin-gated by the controller. Self-unsubscribe at /unsubscribe
| is also public so users can opt out without an account.
|
*/

Route::prefix('api/module/newsletter')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.newsletter.')
    ->group(function () {
        Route::get('/', [NewsletterApiController::class, 'index'])->name('index');
        Route::post('/', [NewsletterApiController::class, 'store'])->name('store');
        Route::post('/unsubscribe', [NewsletterApiController::class, 'unsubscribe'])->name('unsubscribe');
        Route::get('/{id}', [NewsletterApiController::class, 'show'])->whereNumber('id')->name('show');
    });

Route::prefix('api/module/newsletter')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:newsletter:write'])
    ->name('api.module.newsletter.')
    ->group(function () {
        Route::put('/{id}', [NewsletterApiController::class, 'update'])->whereNumber('id')->name('update');
        Route::patch('/{id}', [NewsletterApiController::class, 'update'])->whereNumber('id')->name('update.partial');
        Route::delete('/{id}', [NewsletterApiController::class, 'destroy'])->whereNumber('id')->name('destroy');
    });
