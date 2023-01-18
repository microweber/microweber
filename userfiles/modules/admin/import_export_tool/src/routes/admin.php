<?php


Route::name('admin.import-export-tool.')
    ->prefix(ADMIN_PREFIX . '/import-export-tool')
    ->middleware(['admin', \MicroweberPackages\Modules\Admin\ImportExportTool\Http\Middleware\InstallationMiddleware::class])
    ->group(function () {


        Route::get('test-livewire-counter', \MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Counter::class);
      //  Route::get('test-livewire-counter', \App\Http\Livewire\Counter::class);


    });


Route::name('admin.import-export-tool.')
    ->prefix(ADMIN_PREFIX . '/import-export-tool')
    ->middleware(['admin', \MicroweberPackages\Modules\Admin\ImportExportTool\Http\Middleware\InstallationMiddleware::class])
    //->namespace('MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire')
    ->group(function () {

        Route::get('/', \MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Counter::class)->name('index');
        Route::get('/index', \MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Counter::class)->name('index');

        Route::get('/import-wizard', \App\Http\Livewire\Counter::class)->name('import-wizard');


    });

Route::name('admin.import-export-tool.')
    ->prefix(ADMIN_PREFIX . '/import-export-tool')
    ->middleware(['admin', \MicroweberPackages\Modules\Admin\ImportExportTool\Http\Middleware\InstallationMiddleware::class])
    ->namespace('MicroweberPackages\Modules\Admin\ImportExportTool\Http\Controllers\Admin')
    ->group(function () {

        Route::post('/upload-feed', 'UploadFeedController@upload')->name('upload-feed');


        Route::get('/export-wizard', 'ExportWizardController@index')->name('export-wizard');
        Route::get('/export-wizard/file/{id}', 'ExportWizardController@file')->name('export-wizard-file');
        Route::get('/delete-wizard/file/{id}', 'ExportWizardController@deleteFile')->name('delete-wizard-file');


        Route::get('/install', 'InstallController@index')->name('install');
        Route::get('/import/{id}', 'AdminController@import')->name('import');
        Route::get('/import-start/{id}', 'AdminController@importStart')->name('import-start');
        Route::get('/import-delete/{id}', 'AdminController@importDelete')->name('import-delete');


        Route::get('/index-exports', 'AdminController@exports')->name('index-exports');

    });
