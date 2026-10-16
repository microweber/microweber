<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use MicroweberPackages\App\Http\Controllers\Api\ApiIndexController;
use MicroweberPackages\App\Http\Controllers\Api\LangApiController;
use MicroweberPackages\App\Http\Controllers\Api\UpdateApiController;
use MicroweberPackages\App\Http\Middleware\SessionlessMiddleware;
use MicroweberPackages\App\Http\Middleware\XSS;

/*
|--------------------------------------------------------------------------
| App package API routes (formerly api_expose* in functions/*.php)
|--------------------------------------------------------------------------
| Loaded from AppServiceProvider::register() so they register BEFORE the
| greedy api/{all} catch-all in routes/web.php.
*/

// Public
Route::prefix('api')
    ->middleware(['api.public', XSS::class])
    ->group(function () {
        Route::any('api_index', [ApiIndexController::class, 'index'])
            ->name('api.api_index');
    });

// Public (install / admin-gated inside FormRequest)
Route::prefix('api')
    ->middleware(['api.public', XSS::class])
    ->group(function () {
        Route::any('mw_composer_install_package_by_name', [UpdateApiController::class, 'composerInstallPackageByName'])
            ->name('api.mw_composer_install_package_by_name')
            ->middleware(['throttle:10,1']);
    });

// Admin update / marketplace
Route::prefix('api')
    ->middleware(['admin', 'api', XSS::class])
    ->group(function () {
        Route::any('mw_install_market_item', [UpdateApiController::class, 'installMarketItem'])
            ->name('api.mw_install_market_item');
        Route::any('mw_apply_updates', [UpdateApiController::class, 'applyUpdates'])
            ->name('api.mw_apply_updates');
        Route::any('mw_send_anonymous_server_data', [UpdateApiController::class, 'sendAnonymousServerData'])
            ->name('api.mw_send_anonymous_server_data');

        Route::any('send_lang_form_to_microweber', [LangApiController::class, 'sendLangFormToMicroweber'])
            ->name('api.send_lang_form_to_microweber');
        Route::any('save_language_file_content', [LangApiController::class, 'saveLanguageFileContent'])
            ->name('api.save_language_file_content');
    });

// Sessionless public aliases (formerly /api_nosession/* via SessionlessMiddleware)
Route::prefix('api_nosession')
    ->middleware(['api.public', SessionlessMiddleware::class, XSS::class])
    ->group(function () {
        Route::any('api_index', [ApiIndexController::class, 'index'])
            ->name('api_nosession.api_index');
    });
