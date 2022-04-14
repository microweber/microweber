<?php

Route::name('admin.import-export-tool.')
    ->prefix(ADMIN_PREFIX . '/import-export-tool')
    ->middleware(['admin'])
    ->namespace('MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin')
    ->group(function () {

        Route::get('/index', 'AdminController@index')->name('index');
        Route::get('/import/{id}', 'AdminController@import')->name('import');

    });
