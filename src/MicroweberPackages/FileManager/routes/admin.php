<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 9/15/2020
 * Time: 2:56 PM
 */

Route::name('admin.')
    ->prefix('admin')
    ->namespace('\MicroweberPackages\FileManager\Http\Controllers\Admin')
    ->middleware(['xss','admin'])
    ->group(function () {

    Route::get('file-manager/list', 'FileManager@listFiles');


});