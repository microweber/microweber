<?php


use  \Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'web', 'namespace' => '\MicroweberPackages\App\Http\Controllers'], function ($slug) {

    Route::any('/', 'FrontendController@index')->name('home');


   Route::any('{any}', array('as' => 'all', 'uses' => 'FrontendController@index'))->where('all', '.*');

});

