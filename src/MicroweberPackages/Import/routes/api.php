<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/12/2020
 * Time: 2:36 PM
 */



Route::name('admin.import.')
    ->prefix(ADMIN_PREFIX.'/import')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Backup\Http\Controllers\Admin')
    ->group(function () {

        Route::get('import', 'BackupController@import')->name('import');
        Route::get('export', 'BackupController@export')->name('export');
        Route::get('download', 'BackupController@download')->name('download');

        Route::post('upload', 'BackupController@upload')->name('upload');
        Route::post('delete', 'BackupController@delete')->name('delete');

        Route::post('/language/export', 'LanguageController@export')->name('language.export');
        Route::post('/language/upload', 'LanguageController@upload')->name('language.upload');

    });
