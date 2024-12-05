<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 7/16/2020
 * Time: 2:17 PM
 */

use  \Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api','admin']], function () {

    Route::post('/plupload', [\MicroweberPackages\FileManager\Http\Controllers\PluploadController::class, 'upload'])
        ->name('plupload.upload');

});
