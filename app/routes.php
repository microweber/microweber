<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::any('/', '\Microweber\Controllers\DefaultController@index');
Route::any('/{slug}', '\Microweber\Controllers\DefaultController@index');
Route::any('/api', '\Microweber\Controllers\DefaultController@api');
Route::any('/api/{slug}', '\Microweber\Controllers\DefaultController@api');
Route::any('/module/{slug}', '\Microweber\Controllers\DefaultController@module');

//Route::get('/', function()
//{
//	return View::make('hello');
//});
Route::any('api/{all}', function(){
    return '\Microweber\Controllers\DefaultController@api';
})->where('all', '.*');

Route::any('{all}', function(){
    return '\Microweber\Controllers\DefaultController@index';
})->where('all', '.*');