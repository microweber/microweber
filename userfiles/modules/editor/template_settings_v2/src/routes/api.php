<?php


Route::name('api.editor.template_settings_v2.')
    ->prefix('api/editor/template_settings_v2')
    ->middleware(['admin', 'api'])
    ->namespace('\MicroweberPackages\Editor\TemplateSettingsV2\Http\Controllers')
    ->group(function () {

        \Route::get('list', 'EditorTemplateSettingsV2Controller@getSettings')->name('settings');

    });
