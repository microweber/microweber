<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 7/16/2020
 * Time: 2:17 PM
 */

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api', 'admin']], function () {

    Route::post('/plupload', [\Modules\FileManager\Http\Controllers\PluploadController::class, 'upload'])
        ->name('plupload.upload');

});
Route::name('api.')
    ->prefix('api')
    ->middleware(['api', 'xss', 'admin'])
    ->group(function () {

        Route::get('file-manager/list', \Modules\FileManager\Http\Controllers\Api\FileManagerApiController::class . '@list')->name('file-manager.list');
        Route::delete('file-manager/file', \Modules\FileManager\Http\Controllers\Api\FileManagerApiController::class . '@delete')->name('file-manager.delete');
        Route::post('file-manager/create-folder', \Modules\FileManager\Http\Controllers\Api\FileManagerApiController::class . '@createFolder')->name('file-manager.create-folder');
        Route::post('file-manager/rename', \Modules\FileManager\Http\Controllers\Api\FileManagerApiController::class . '@rename')->name('file-manager.rename');


    });


