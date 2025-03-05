<?php

use \Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'web'], function () {


    Route::get('template/preview-layout', function () {

        $layout = request()->get('layout');
        $layout = str_replace('..', '', $layout);
        $layout = str_replace('/', '', $layout);
        $template = app()->template_manager->name();

        $is_laravel_template = app()->template_manager->is_laravel_template($template);

        if (!$is_laravel_template) {
            return 'This is not a Laravel template';
        }

        $laravelTemplate = app()->templates->find($template);
        $lowerName = $laravelTemplate->getLowerName();

        return view('microweber-live-edit::preview-layout.layout_render', ['layoutFile' => $layout, 'templateViewsName' => $lowerName,'template' => $template]);

    });


});
