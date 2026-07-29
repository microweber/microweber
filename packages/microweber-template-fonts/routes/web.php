<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use MicroweberPackages\TemplateFonts\Http\Controllers\TemplateFontsController;

/*
|--------------------------------------------------------------------------
| Template Fonts Routes
|--------------------------------------------------------------------------
|
| Compatible with existing Microweber frontend (FontsManager.vue /
| live-edit-font-manager.js) path names under api/template/*.
|
*/

$prefix = (string) config('template-fonts.route_prefix', 'api/template');
$namePrefix = (string) config('template-fonts.route_name_prefix', 'api.template.');
$adminMiddleware = config('template-fonts.admin_middleware', ['admin']);
if (!is_array($adminMiddleware)) {
    $adminMiddleware = ['admin'];
}

// Public CSS endpoint (no admin required — frontend needs it)
Route::any(
    (string) config('template-fonts.public_css_route', 'api/template/print_custom_css_fonts'),
    [TemplateFontsController::class, 'printCustomCssFonts']
)->name('print_custom_css_fonts');

// Admin-ish font management API
Route::middleware($adminMiddleware)
    ->prefix($prefix)
    ->name($namePrefix)
    ->group(function () {
        Route::get('get-fonts', [TemplateFontsController::class, 'getFonts'])
            ->name('get-fonts');

        Route::get('get-favorite-fonts', [TemplateFontsController::class, 'getFavoriteFonts'])
            ->name('get-favorite-fonts');

        Route::post('remove-favorite-font', [TemplateFontsController::class, 'removeFavoriteFont'])
            ->name('remove-favorite-font');

        Route::post('save-template-fonts', [TemplateFontsController::class, 'saveTemplateFonts'])
            ->name('save-template-fonts');

        Route::post('upload-custom-font', [TemplateFontsController::class, 'uploadCustomFont'])
            ->name('upload-custom-font');
    });
