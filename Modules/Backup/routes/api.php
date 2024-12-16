<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/12/2020
 * Time: 2:36 PM
 */

use Illuminate\Support\Facades\Route;
use Modules\Backup\Http\Controllers\Admin\LanguageController;
use \Modules\Backup\Http\Controllers\Admin\BackupController;


Route::prefix(admin_url() . '/api/backup')
    ->middleware(['admin'])
    ->group(function () {

        Route::get('restore', BackupController::class . '@restore')->name('admin.backup.restore');
        Route::get('start', BackupController::class . '@start')->name('admin.backup.start');
        Route::get('download', BackupController::class . '@download')->name('admin.backup.download');
        Route::get('generate-session-id', BackupController::class . '@generateSessionId')->name('admin.backup.generate-session-id');

        Route::post('upload', BackupController::class . '@upload')->name('admin.backup.upload');
        Route::post('delete', BackupController::class . '@delete')->name('admin.backup.delete');

        Route::post('/language/export', LanguageController::class . '@export')->name('admin.backup.language.export');
        Route::post('/language/upload', LanguageController::class . '@upload')->name('admin.backup.language.upload');

    });
