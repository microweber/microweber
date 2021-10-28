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

    Route::get('file-manager/list', 'FileManagerApiController@listFiles')->name('file-manager.list');


});
