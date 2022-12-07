<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 7/16/2020
 * Time: 2:17 PM
 */


Route::group(['namespace' => '\MicroweberPackages\FileManager\Http\Controllers'], function () {

    Route::post('/plupload', 'PluploadController@upload');

});
