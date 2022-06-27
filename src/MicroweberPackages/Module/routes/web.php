<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 7/16/2020
 * Time: 2:17 PM
 */

Route::group(['namespace' => '\MicroweberPackages\Module\Http\Controllers'], function () {

    Route::post('/plupload', 'ModuleController@plupload')->middleware([
         \MicroweberPackages\App\Http\Middleware\SameSiteRefererMiddleware::class,
         \MicroweberPackages\App\Http\Middleware\IsAjaxMiddleware::class,
         \MicroweberPackages\App\Http\Middleware\VerifyCsrfToken::class
    ]);
   // Route::any('plupload/{all}', array('as' => 'plupload', 'uses' => 'ModuleController@plupload'))->where('all', '.*');

    //Route::any('/module/', 'ModuleController@index');
    //Route::any('module/{all}', array('as' => 'module', 'uses' => 'ModuleController@index'))->where('all', '.*');

});
