<?php

use \Illuminate\Support\Facades\Response;
use \Illuminate\Support\Facades\Route;
use \Illuminate\Http\Request;

Route::name('api.template.')
    ->prefix('api/template')
    ->middleware(['api', 'admin'])
    ->group(function () {

        Route::get('template-settings-sidebar', function () {

            return view('template::template-settings-sidebar-render-component');
        });


        Route::namespace('MicroweberPackages\Template\Http\Controllers\Api')->group(function () {
            Route::get('change', 'TemplateApiController@change')->name('change');

            if (config('microweber.allow_php_files_upload')) {
                Route::post('upload', 'TemplateApiController@upload')->name('upload');
            }
        });

        Route::get('compile_admin_css', function () {
            $compile = app()->template_manager->admin->compileAdminCss();

            $response = Response::make($compile);
            $response->header('Content-Type', 'text/css');
            return $response;

        })->name('compile_admin_css')->withoutMiddleware(['api', 'admin']);


        Route::get('compile_admin_live_edit_css', function () {
            $compile = app()->template_manager->admin->compileLiveEditCss();

            $response = Response::make($compile);
            $response->header('Content-Type', 'text/css');
            return $response;

        })->name('compile_admin_live_edit_css')->withoutMiddleware(['api', 'admin']);


        Route::get('get_admin_css_url', function () {
            app()->template_manager->admin->cleanCompiledStylesheets();
            $main_css_url = app()->template_manager->get_admin_system_ui_css_url();
            return $main_css_url;

        })->name('get_admin_css_url');


        Route::get('reset_admin_stylesheet', function () {
            app()->template_manager->admin->cleanCompiledStylesheets();

            return app()->template_manager->admin->resetSelectedStyle();

        })->name('reset_admin_stylesheet');

        Route::get('reset_admin_stylesheet_colors', function () {
            app()->template_manager->admin->cleanCompiledStylesheets();
            return app()->template_manager->admin->resetSelectedStyleVariables();

        })->name('reset_admin_stylesheet_colors');

    });

Route::post('api/current_template_save_custom_css', function (Request $request) {
    $data = $request->all();
    app()->template_manager->defineConstants($data);

    return mw()->layouts_manager->template_save_css($data);
})->name('current_template_save_custom_css')->middleware(['api', 'admin']);

Route::post('api/layouts/template_remove_custom_css', function (Request $request) {
    $data = $request->all();
    app()->template_manager->defineConstants($data);

    return mw()->layouts_manager->template_remove_custom_css($data);
})->name('template_remove_custom_css')
    ->middleware(['api', 'admin']);


//\Route::post('api/template/delete_compiled_css', function (Request  $request) {
//    $data = $request->all();
//    app()->template_manager->defineConstants($data);
//
//    return app()->template_manager->delete_compiled_css($data);
//})->name('current_template_save_custom_css')
//->middleware(['admin']);

Route::get('api/template/delete_compiled_css', function (Request $request) {
    $data = $request->all();
    app()->template_manager->defineConstants($data);

    $compiled = app()->template_manager->delete_compiled_css($data);

    // $compiled =  app()->template_manager->compile_css($data);

    $compiled = str_replace('../../../../../../', userfiles_url(), $compiled);

    $response = Response::make($compiled);
    $response->header('Content-Type', 'text/css');
    return $response;
})->name('delete_compiled_css')
    ->middleware(['api', 'admin']);


Route::get('api/template/compile_css', function (Request $request) {
    $data = $request->all();
    app()->template_manager->defineConstants($data);

    $compiled = app()->template_manager->compile_css($data);

    $compiled = str_replace('../../../../../../', userfiles_url(), $compiled);

    $response = Response::make($compiled);
    $response->header('Content-Type', 'text/css');
    return $response;
})->name('template_compile_css')->middleware(['api','admin']);


Route::any('api/template/print_custom_css_fonts', function (Request $request) {

    $contents = app()->template_manager->get_custom_fonts_css_content();

    $response = Response::make($contents);
    $response->header('Content-Type', 'text/css');

    return $response;
})->name('print_custom_css_fonts');


Route::any('api/template/print_custom_css', function (Request $request) {

    $data = $request->all();
    $contents = app()->template_manager->get_custom_css($data);

    $response = Response::make($contents);
    $response->header('Content-Type', 'text/css');

    return $response;
})->name('print_custom_css');














