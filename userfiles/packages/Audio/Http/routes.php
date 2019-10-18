<?php

Route::group(['middleware' => 'module', 'prefix' => 'audio', 'namespace' => 'Microweber\\Packages\Audio\Http\Controllers'], function() {
    Route::get('/', 'AudioController@index');
    Route::get('/admin', 'AudioController@admin');
});
