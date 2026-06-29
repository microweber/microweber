<?php

use Illuminate\Support\Facades\Route;
use MicroweberPackages\Translation\Http\Controllers\TranslationController;

// Translation management is admin-only — the `admin` middleware GROUP gates
// every endpoint (read + write) so an unauthenticated/non-admin user can never
// list, export, save, import or install translations. The `admin` group
// (AppServiceProvider) bundles the session stack + the Admin auth middleware
// (is_admin() check → 401 for AJAX / redirect to admin login). Mirrors the
// legacy web.php routes and the other admin module APIs (Menu/Ai/Backup).
Route::prefix('api/translations')
    ->middleware(['admin', 'api'])
    ->group(function () {
        Route::get('/', [TranslationController::class, 'index']);
        Route::post('/save', [TranslationController::class, 'save']);
        Route::get('/export', [TranslationController::class, 'export']);
        Route::post('/import', [TranslationController::class, 'importFromJson']);
        Route::get('/languages', [TranslationController::class, 'availableLanguages']);
        Route::post('/install-language', [TranslationController::class, 'installLanguage']);
    });