<?php

use MicroweberPackages\Import\Formats\CsvReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportFeedToDatabase;
use MicroweberPackages\Modules\Admin\ImportExportTool\ImportMapping\FeedMapToArray;

Route::name('admin.import-export-tool.')
    ->prefix(ADMIN_PREFIX . '/import-export-tool')
    ->middleware(['admin', \MicroweberPackages\Modules\Admin\ImportExportTool\Http\Middleware\InstallationMiddleware::class])
    ->namespace('MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin')
    ->group(function () {

        Route::post('/upload-feed', 'UploadFeedController@upload')->name('upload-feed');

        Route::get('/import-wizard', 'ImportWizardController@index')->name('import-wizard');

        Route::get('/export-wizard', 'ExportWizardController@index')->name('export-wizard');
        Route::get('/export-wizard/file/{id}', 'ExportWizardController@file')->name('export-wizard-file');
        Route::get('/delete-wizard/file/{id}', 'ExportWizardController@deleteFile')->name('delete-wizard-file');

        Route::get('/', 'AdminController@index')->name('index');
        Route::get('/install', 'InstallController@index')->name('install');
        Route::get('/index', 'AdminController@index')->name('index');
        Route::get('/import/{id}', 'AdminController@import')->name('import');
        Route::get('/import-start/{id}', 'AdminController@importStart')->name('import-start');
        Route::get('/import-delete/{id}', 'AdminController@importDelete')->name('import-delete');


        Route::get('/index-exports', 'AdminController@exports')->name('index-exports');

    });
