<?php

use \Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'web'], function () {


    Route::get('template/preview-layout', function () {


        $module = request()->get('module') ?? 'layouts';
        $skin = request()->get('skin') ?? 'default';
        $skin = str_replace('..', '', $skin);
        $skin = str_replace('/', '', $skin);
        $template = app()->template_manager->name();

        $is_laravel_template = app()->template_manager->is_laravel_template($template);

        if (!$is_laravel_template) {
            return 'This is not a Laravel template';
        }

        $laravelTemplate = app()->templates->find($template);
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
