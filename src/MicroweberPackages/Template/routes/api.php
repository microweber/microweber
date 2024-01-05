<?php


use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Export\SessionStepper;
use MicroweberPackages\Import\Import;
use \Illuminate\Http\Request;
use \Illuminate\Http\Response;

Route::name('api.template.')
    ->prefix('api/template')
    ->middleware(['api', 'admin'])
    ->group(function () {

        Route::get('template-settings-sidebar', function() {

            return view('template::template-settings-sidebar-render-component');
        });


        Route::namespace('MicroweberPackages\Template\Http\Controllers\Api')->group(function () {
            \Route::get('change', 'TemplateApiController@change')->name('change');

            if (config('microweber.allow_php_files_upload')) {
                \Route::post('upload', 'TemplateApiController@upload')->name('upload');
            }
        });

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

\Route::post('api/current_template_save_custom_css', function (Request $request) {
    $data = $request->all();
    app()->template->defineConstants($data);

    return mw()->layouts_manager->template_save_css($data);
})->name('current_template_save_custom_css')
->middleware(['admin']);

\Route::post('api/layouts/template_remove_custom_css', function (Request $request) {
    $data = $request->all();
    app()->template->defineConstants($data);

    return mw()->layouts_manager->template_remove_custom_css($data);
})->name('template_remove_custom_css')
->middleware(['admin']);


//\Route::post('api/template/delete_compiled_css', function (Request  $request) {
//    $data = $request->all();
//    app()->template->defineConstants($data);
//
//    return mw()->template->delete_compiled_css($data);
//})->name('current_template_save_custom_css')
//->middleware(['admin']);

\Route::get('api/template/delete_compiled_css', function (Request $request) {
    $data = $request->all();
    app()->template->defineConstants($data);

    $compiled =  mw()->template->compile_css($data);

    $compiled = str_replace( '../../../../../../',userfiles_url(), $compiled);

    $response = \Response::make($compiled);
    $response->header('Content-Type', 'text/css');
    return $response;
})->name('delete_compiled_css')
->middleware(['admin']);


\Route::get('api/template/compile_css', function (Request $request) {
    $data = $request->all();
    app()->template->defineConstants($data);

    $compiled =  mw()->template->compile_css($data);

    $compiled = str_replace( '../../../../../../',userfiles_url(), $compiled);

    $response = \Response::make($compiled);
    $response->header('Content-Type', 'text/css');
    return $response;
})->name('template_compile_css')
->middleware(['admin']);

















