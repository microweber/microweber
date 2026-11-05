<?php

use \Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'web'], function () {


    // Universal layout-skin preview — renders a single <module type="…"
    // template="…"> inside a template's master so any layout can be previewed on
    // any template (e.g. to compare the same layout across templates side by
    // side). Query params:
    //   module   — the module type (default "layouts")
    //   skin     — the skin path; "/" and "." both work ("ecommerce/skin-1" or
    //              "ecommerce.skin-1"); traversal is stripped
    //   template — OPTIONAL: preview under this template's master instead of the
    //              active one. Falls back to the active template when absent.
    Route::get('template/preview-layout', function () {

        $module = request()->get('module') ?: 'layouts';

        $skin = (string) (request()->get('skin') ?? 'default');
        $skin = str_replace('..', '', $skin);        // no traversal
        $skin = str_replace('/', '.', $skin);        // normalize separators → dots
        $skin = trim($skin, '.');

        // Optional template override so this works with ANY template, not only the
        // active one. Validated against installed templates below.
        $template = request()->get('template') ?: app()->template_manager->name();
        $template = preg_replace('/[^A-Za-z0-9_\-]/', '', (string) $template);

        if (!app()->template_manager->is_laravel_template($template)) {
            return 'This is not a Laravel template';
        }

        $laravelTemplate = app()->templates->find($template);
        if (!$laravelTemplate) {
            return 'Template not found: ' . e($template);
        }
        $lowerName = $laravelTemplate->getLowerName();

        return view('microweber-live-edit::preview-layout.layout_render', [
            'module' => $module,
            'skin' => $skin,
            'templateViewsName' => $lowerName
            ,'template' => $template]);

    });


});




Route::middleware(['admin'])
    ->prefix(mw_admin_prefix_url())
    ->name('admin.setup-wizard.')
    ->group(function () {
        Route::get('/setup-wizard', [
            \MicroweberPackages\LiveEdit\Http\Controllers\SetupWizardController::class,
            'index'
        ])->name('index');

        Route::post('/install-template', [
            \MicroweberPackages\LiveEdit\Http\Controllers\SetupWizardController::class,
            'installTemplate'
        ])->name('install-template');
    });
