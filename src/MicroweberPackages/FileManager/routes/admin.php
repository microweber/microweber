<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 9/15/2020
 * Time: 2:56 PM
 */

use  \Illuminate\Support\Facades\Route;

Route::name('api.')
    ->prefix('api')
    // ->namespace('\MicroweberPackages\FileManager\Http\Controllers\Api')
    ->middleware(['api', 'xss', 'admin'])
    ->group(function () {

        Route::get('file-manager/list', \MicroweberPackages\FileManager\Http\Controllers\Api\FileManagerApiController::class . '@list')->name('file-manager.list');
        Route::delete('file-manager/file', \MicroweberPackages\FileManager\Http\Controllers\Api\FileManagerApiController::class . '@delete')->name('file-manager.delete');
        Route::post('file-manager/create-folder', \MicroweberPackages\FileManager\Http\Controllers\Api\FileManagerApiController::class . '@createFolder')->name('file-manager.create-folder');
        Route::post('file-manager/rename', \MicroweberPackages\FileManager\Http\Controllers\Api\FileManagerApiController::class . '@rename')->name('file-manager.rename');


    });


