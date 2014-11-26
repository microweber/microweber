<?php

Route::group(['namespace' => '\Microweber\Controllers'], function() {

	Route::any('/', 'DefaultController@index');
	//Route::any('/{slug}', 'DefaultController@index');
	//Route::any('/apijs/{slug}', 'DefaultController@apijs');

	Route::any('/api', 'DefaultController@api');
	Route::any('/api/{slug}', 'DefaultController@api');
	Route::any('/module/{slug}', 'DefaultController@module');
	//Route::any('/admin', 'DefaultController@admin');

	//Route::any('admin/{all}', function(){
	//    return 'DefaultController@admin';
	//})->where('all', '/.*');
	Route::any('/admin', 'AdminController@index');
	Route::any('/admin/', 'AdminController@index');
	Route::any('/admin/{slashData?}', 'AdminController@index')
	    ->where('slashData', '(.*)');

	//Route::group(array('prefix' => 'admin', 'namespace' => 'Admin', 'before' => 'auth'), function()
	//{
	//    Route::get('/', 'DefaultController@admin');
	//});


	//Route::get('/', function()
	//{
	//	return View::make('hello');
	//});

	Route::get('api/{all}', array(
	    'as' => 'api',
	    'uses' => 'DefaultController@api'
	))->where('all', '.*');;
	Route::any('/apijs', 'DefaultController@apijs');
	Route::any('/apijs_settings', 'DefaultController@apijs_settings');
	Route::get('apijs/{all}', array(
	    'as' => 'apijs',
	    'uses' => 'DefaultController@apijs'
	))->where('all', '.*');;
	Route::get('{all}', array(
	    'as' => 'all',
	    'uses' => 'DefaultController@index'
	))->where('all', '.*');;


	//Route::any('api/{all}', function(){
	//    return 'DefaultController@api';
	//})->where('all', '.*');

	//Route::any('{all}', function(){
	//    return 'DefaultController@indsssex';
	//})->where('all', '.*');


});