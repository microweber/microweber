<?php

Route::name('admin.import-export-tool.')
    ->prefix(ADMIN_PREFIX . '/import-export-tool')
    ->middleware(['admin'])
    ->namespace('MicroweberPackages\Modules\StandaloneUpdater\Http\Controllers')
    ->group(function () {

        Route::get('index', 'StandaloneUpdaterController@aboutNewVersion')->name('index');

    });
