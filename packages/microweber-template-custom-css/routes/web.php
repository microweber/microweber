<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use MicroweberPackages\TemplateCustomCss\Http\Controllers\TemplateCustomCssController;

/*
|--------------------------------------------------------------------------
| Template Custom CSS Routes
|--------------------------------------------------------------------------
|
| Compatible with existing Microweber frontend (render-css-editor.blade.php /
| live edit palette save) path names.
|
*/

$adminMiddleware = config('template-custom-css.admin_middleware', ['api', 'admin']);
if (!is_array($adminMiddleware)) {
    $adminMiddleware = ['api', 'admin'];
}

// Public CSS print endpoint (no admin — frontend needs it)
Route::any(
    'api/' . ltrim((string) config('template-custom-css.print_custom_css_route', 'template/print_custom_css'), '/'),
    [TemplateCustomCssController::class, 'printCustomCss']
)->name('print_custom_css');

// Admin CSS management
$saveLiveEditName = (string) config('template-custom-css.save_live_edit_route', 'current_template_save_custom_css');
$removeName = (string) config('template-custom-css.remove_css_route', 'layouts/template_remove_custom_css');

// CSS-mutating endpoints add CSRF on top of the admin middleware (matches the
// CMS security hardening for these routes). Prefer an explicitly configured
// middleware class; otherwise fall back to the CMS VerifyCsrfToken when present.
$mutatingMiddleware = $adminMiddleware;
$csrf = config('template-custom-css.csrf_middleware');
if (!is_string($csrf) || $csrf === '' || !class_exists($csrf)) {
    $csrf = \MicroweberPackages\App\Http\Middleware\VerifyCsrfToken::class;
}
if (class_exists($csrf) && !in_array($csrf, $mutatingMiddleware, true)) {
    $mutatingMiddleware[] = $csrf;
}

Route::post(
    'api/' . ltrim($saveLiveEditName, '/'),
    [TemplateCustomCssController::class, 'saveLiveEditCss']
)->name('current_template_save_custom_css')
    ->middleware($mutatingMiddleware);

Route::post(
    'api/' . ltrim($removeName, '/'),
    [TemplateCustomCssController::class, 'removeCustomCss']
)->name('template_remove_custom_css')
    ->middleware($mutatingMiddleware);

Route::post(
    'api/' . ltrim((string) config('template-custom-css.save_custom_css_route', 'template/save_custom_css'), '/'),
    [TemplateCustomCssController::class, 'saveCustomCss']
)->name('api.template.save_custom_css')
    ->middleware($mutatingMiddleware);

Route::post(
    'api/template/validate_css',
    [TemplateCustomCssController::class, 'validateCss']
)->name('api.template.validate_css')
    ->middleware($adminMiddleware);

Route::get(
    'api/template/live_edit_css_url',
    [TemplateCustomCssController::class, 'liveEditCssUrl']
)->name('api.template.live_edit_css_url')
    ->middleware($adminMiddleware);
