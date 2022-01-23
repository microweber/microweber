<?php


Route::name('api.template.')
    ->prefix('api/template')
    ->middleware(['api', 'admin'])
    ->group(function () {

        \Route::get('compile_admin_css', function () {
           $compile = app()->template->admin->compileAdminCss();

            $response = \Response::make($compile);
            $response->header('Content-Type', 'text/css');
            return $response;

        })->name('compile_admin_css')->withoutMiddleware(['api','admin']);


        \Route::get('compile_admin_live_edit_css', function () {
           $compile = app()->template->admin->compileLiveEditCss();

            $response = \Response::make($compile);
            $response->header('Content-Type', 'text/css');
            return $response;

        })->name('compile_admin_live_edit_css')->withoutMiddleware(['api','admin']);



        \Route::get('get_admin_css_url', function () {
            mw()->template->admin->cleanCompiledStylesheets();
            $main_css_url = app()->template->get_admin_system_ui_css_url();
            return $main_css_url;

        })->name('get_admin_css_url');


        \Route::get('reset_admin_stylesheet', function () {
            mw()->template->admin->cleanCompiledStylesheets();

            return mw()->template->admin->resetSelectedStyle();

        })->name('reset_admin_stylesheet');

      \Route::get('reset_admin_stylesheet_colors', function () {
            mw()->template->admin->cleanCompiledStylesheets();
             return mw()->template->admin->resetSelectedStyleVariables();

        })->name('reset_admin_stylesheet_colors');




    });




api_expose('template/compile_css', function ($params) {
    return mw()->template->compile_css($params);
});

api_expose_admin('template/delete_compiled_css', function ($params) {
    return mw()->template->delete_compiled_css($params);
});













