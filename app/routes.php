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
//Route::any('/{slug}', '\Microweber\Controllers\DefaultController@index');
//Route::any('/apijs/{slug}', '\Microweber\Controllers\DefaultController@apijs');

Route::any('/api', '\Microweber\Controllers\DefaultController@api');
Route::any('/api/{slug}', '\Microweber\Controllers\DefaultController@api');
Route::any('/module/{slug}', '\Microweber\Controllers\DefaultController@module');
//Route::any('/admin', '\Microweber\Controllers\DefaultController@admin');

//Route::any('admin/{all}', function(){
//    return '\Microweber\Controllers\DefaultController@admin';
//})->where('all', '/.*');
Route::any('/admin', '\Microweber\Controllers\AdminController@index');
Route::any('/admin/', '\Microweber\Controllers\AdminController@index');
Route::any('/admin/{slashData?}', '\Microweber\Controllers\AdminController@index')
    ->where('slashData', '(.*)');

//Route::group(array('prefix' => 'admin', 'namespace' => 'Admin', 'before' => 'auth'), function()
//{
//    Route::get('/', '\Microweber\Controllers\DefaultController@admin');
//});


//Route::get('/', function()
//{
//	return View::make('hello');
//});

Route::get('api/{all}', array(
    'as' => 'api',
    'uses' => '\Microweber\Controllers\DefaultController@api'
))->where('all', '.*');;
Route::any('/apijs', '\Microweber\Controllers\DefaultController@apijs');
Route::any('/apijs_settings', '\Microweber\Controllers\DefaultController@apijs_settings');
Route::get('apijs/{all}', array(
    'as' => 'apijs',
    'uses' => '\Microweber\Controllers\DefaultController@apijs'
))->where('all', '.*');;
Route::get('{all}', array(
    'as' => 'all',
    'uses' => '\Microweber\Controllers\DefaultController@index'
))->where('all', '.*');;


//Route::any('api/{all}', function(){
//    return '\Microweber\Controllers\DefaultController@api';
//})->where('all', '.*');

//Route::any('{all}', function(){
//    return '\Microweber\Controllers\DefaultController@indsssex';
//})->where('all', '.*');


