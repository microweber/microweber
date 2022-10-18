<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 9/15/2020
 * Time: 2:56 PM
 */

Route::name('api.')
    ->prefix('api')
    ->namespace('\MicroweberPackages\FileManager\Http\Controllers\Api')
    ->middleware(['xss','admin'])
    ->group(function () {

    Route::get('file-manager/list', 'FileManagerApiController@list')->name('file-manager.list');
    Route::delete('file-manager/file', 'FileManagerApiController@delete')->name('file-manager.delete');
    Route::put('file-manager/file', 'FileManagerApiController@modify')->name('file-manager.modify');

});
