<?php




Route::group(['middleware' => 'public.web', 'namespace' => '\MicroweberPackages\App\Http\Controllers'], function ($slug) {

    Route::any('/', 'FrontendController@index')->name('home');

//dd($slug);
   Route::any('{any}', array('as' => 'all', 'uses' => 'FrontendController@index'))->where('all', '.*');

});

