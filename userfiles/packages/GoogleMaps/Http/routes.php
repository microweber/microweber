<?php

Route::group(['middleware' => 'web', 'prefix' => 'googlemaps', 'namespace' => 'Microweber\\Packages\GoogleMaps\Http\Controllers'], function()
{
    Route::get('/', 'GoogleMapsController@index');
});
