<?php

//Route::group(['namespace' => '\Microweber\Controllers'], function() {

Route::any('/', '\Microweber\Controllers\DefaultController@index');
//Route::any('/{slug}', '\Microweber\Controllers\DefaultController@index');
//Route::any('/apijs/{slug}', '\Microweber\Controllers\DefaultController@apijs');

Route::any('/api', '\Microweber\Controllers\DefaultController@api');
Route::any('/api/{slug}', '\Microweber\Controllers\DefaultController@api');

//Route::any('/admin', '\Microweber\Controllers\DefaultController@admin');

//Route::any('admin/{all}', function(){
//    return '\Microweber\Controllers\DefaultController@admin';
//})->where('all', '/.*');
#Route::any('/admin', '\Microweber\Controllers\AdminController@index');
Route::any('/admin', '\Microweber\Controllers\AdminController@index');


Route::any('admin{slash?}', array(
    'as' => 'admin',
    'uses' => '\Microweber\Controllers\AdminController@index'
))->where('slash', '\/');;


//Route::any('/admin/{slashData?}', '\Microweber\Controllers\AdminController@index')
//    ->where('slashData', '(.*)');
Route::any('admin/{all}', array(
    'as' => 'admin',
    'uses' => '\Microweber\Controllers\AdminController@index'
))->where('all', '.*');;


//Route::group(array('prefix' => 'admin', 'namespace' => 'Admin', 'before' => 'auth'), function()
//{
//    Route::get('/', '\Microweber\Controllers\DefaultController@admin');
//});


//Route::get('/', function()
//{
//	return View::make('hello');
//});

Route::any('api/{all}', array(
    'as' => 'api',
    'uses' => '\Microweber\Controllers\DefaultController@api'
))->where('all', '.*');;
Route::any('/apijs', '\Microweber\Controllers\DefaultController@apijs');
Route::any('/apijs_settings', '\Microweber\Controllers\DefaultController@apijs_settings');
Route::any('apijs/{all}', array(
    'as' => 'apijs',
    'uses' => '\Microweber\Controllers\DefaultController@apijs'
))->where('all', '.*');


Route::any('/editor_tools', '\Microweber\Controllers\DefaultController@editor_tools');
Route::any('editor_tools/{all}', array(
    'as' => 'editor_tools',
    'uses' => '\Microweber\Controllers\DefaultController@editor_tools'
))->where('all', '.*');



Route::any('/module/', '\Microweber\Controllers\ModuleController@index');
Route::any('module/{all}', array(
    'as' => 'module',
    'uses' => '\Microweber\Controllers\ModuleController@index'
))->where('all', '.*');;


Route::any('{all}', array(
    'as' => 'all',
    'uses' => '\Microweber\Controllers\DefaultController@index'
))->where('all', '.*');;


//Route::any('api/{all}', function(){
//    return '\Microweber\Controllers\DefaultController@api';
//})->where('all', '.*');

//Route::any('{all}', function(){
//    return '\Microweber\Controllers\DefaultController@indsssex';
//})->where('all', '.*');


//});