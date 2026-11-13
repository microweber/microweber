<?php

use \Illuminate\Support\Facades\Response;
use \Illuminate\Support\Facades\Route;
use \Illuminate\Http\Request;

Route::name('api.template.')
->prefix('api/template')
->middleware(['api', 'admin'])
->group(function () {

// Template preview and customization endpoints
Route::get('preview', [
    \MicroweberPackages\Template\Http\Controllers\TemplatePreviewController::class, 'preview'
])->name('preview');

Route::get('customizations', [
    \MicroweberPackages\Template\Http\Controllers\TemplatePreviewController::class, 'getCustomizations'
])->name('customizations');

Route::post('customizations', [
    \MicroweberPackages\Template\Http\Controllers\TemplatePreviewController::class, 'saveCustomization'
])->name('save-customization');

Route::get('template-settings-sidebar', function () {

            return view('template::template-settings-sidebar-render-component');
        });




        Route::get('template-style-settings',[
            \MicroweberPackages\Template\Http\Controllers\Api\TemplateStyleEditorSettingsController::class , 'templateStyleSettings'
        ])
            ->name('template-style-settings');

        // Font routes moved to microweber-packages/template-fonts
        // (api.template.get-fonts, get-favorite-fonts, remove-favorite-font, save-template-fonts, upload-custom-font)



        // api/template/change
        Route::post('change', MicroweberPackages\Template\Http\Controllers\Api\TemplateApiController::class . '@change')->name('change');

        // Export the CURRENT site as a reusable, PHP-less child template and stream
        // the ZIP (used by the Live-Edit "Export as template" menu item). Admin-only.
        Route::get('export-current', function (Request $request) {
            $source = (string) (app()->option_manager->get('current_template', 'template') ?: 'Base');
            try {
                $info = (new \MicroweberPackages\Template\Export\TemplateExporter())
                    ->setName($source . ' Copy')
                    ->setSource($source)
                    ->exportToZip();
            } catch (\Throwable $e) {
                return Response::make('Export failed: ' . $e->getMessage(), 500);
            }
            $zip = (string) ($info['zip'] ?? '');
            $downloadName = ($info['name'] ?? 'template') . '.zip';
            return \Illuminate\Support\Facades\Response::streamDownload(function () use ($zip) {
                if ($zip !== '' && is_file($zip)) {
                    readfile($zip);
                    @unlink($zip);
                }
            }, $downloadName, ['Content-Type' => 'application/zip']);
        })->name('export-current');


    });

// CSS save/remove/print routes are registered by
// microweber-packages/template-custom-css (see packages/microweber-template-custom-css/routes/web.php).
// print_custom_css_fonts is registered by microweber-packages/template-fonts.

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
})->name('template_compile_css')->middleware(['api', 'admin']);

