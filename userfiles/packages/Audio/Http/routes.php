<?php

Route::group(['middleware' => 'web', 'prefix' => 'audio', 'namespace' => 'Packages\Audio\Http\Controllers'], function()
{
    Route::get('/', 'AudioController@index');
});
