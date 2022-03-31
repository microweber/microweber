<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/12/2020
 * Time: 2:36 PM
 */



Route::name('admin.backup.')
    ->prefix(ADMIN_PREFIX .'/backup')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Backup\Http\Controllers\Admin')
    ->group(function () {

        Route::get('restore', 'BackupController@restore')->name('restore');
        Route::get('start', 'BackupController@start')->name('start');
        Route::get('download', 'BackupController@download')->name('download');
        Route::get('generate-session-id', 'BackupController@generateSessionId')->name('generate-session-id');

        Route::post('upload', 'BackupController@upload')->name('upload');
        Route::post('delete', 'BackupController@delete')->name('delete');

        Route::post('/language/export', 'LanguageController@export')->name('language.export');
        Route::post('/language/upload', 'LanguageController@upload')->name('language.upload');

    });
