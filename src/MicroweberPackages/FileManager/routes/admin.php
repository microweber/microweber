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
    ->namespace('\MicroweberPackages\FileManager\Http\Controllers\Api')
    ->middleware(['xss','admin'])
    ->group(function () {

    Route::get('file-manager/list', 'FileManagerApiController@list')->name('file-manager.list');
    Route::delete('file-manager/file', 'FileManagerApiController@delete')->name('file-manager.delete');
    Route::post('file-manager/create-folder', 'FileManagerApiController@createFolder')->name('file-manager.create-folder');

  /*  Route::patch('file-manager/file', 'FileManagerApiController@rename')->name('file-manager.rename');*/

});


