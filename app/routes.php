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



Route::any('/', '\Weber\Controllers\DefaultController@index');
//Route::any('/{slug}', '\Weber\Controllers\DefaultController@index');
//Route::any('/apijs/{slug}', '\Weber\Controllers\DefaultController@apijs');

Route::any('/api', '\Weber\Controllers\DefaultController@api');
Route::any('/api/{slug}', '\Weber\Controllers\DefaultController@api');
Route::any('/module/{slug}', '\Weber\Controllers\DefaultController@module');
//Route::any('/admin', '\Weber\Controllers\DefaultController@admin');

//Route::any('admin/{all}', function(){
//    return '\Weber\Controllers\DefaultController@admin';
//})->where('all', '/.*');
Route::any('/admin', '\Weber\Controllers\AdminController@index');
Route::any('/admin/', '\Weber\Controllers\AdminController@index');
Route::any('/admin/{slashData?}', '\Weber\Controllers\AdminController@index')
    ->where('slashData', '(.*)');

//Route::group(array('prefix' => 'admin', 'namespace' => 'Admin', 'before' => 'auth'), function()
//{
//    Route::get('/', '\Weber\Controllers\DefaultController@admin');
//});


//Route::get('/', function()
//{
//	return View::make('hello');
//});

Route::get('api/{all}', array(
    'as' => 'api',
    'uses' => '\Weber\Controllers\DefaultController@api'
))->where('all', '.*');;
Route::any('/apijs', '\Weber\Controllers\DefaultController@apijs');
Route::any('/apijs_settings', '\Weber\Controllers\DefaultController@apijs_settings');
Route::get('apijs/{all}', array(
    'as' => 'apijs',
    'uses' => '\Weber\Controllers\DefaultController@apijs'
))->where('all', '.*');;
Route::get('{all}', array(
    'as' => 'all',
    'uses' => '\Weber\Controllers\DefaultController@index'
))->where('all', '.*');;


//Route::any('api/{all}', function(){
//    return '\Weber\Controllers\DefaultController@api';
//})->where('all', '.*');

//Route::any('{all}', function(){
//    return '\Weber\Controllers\DefaultController@indsssex';
//})->where('all', '.*');


