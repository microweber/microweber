<?php
/**
* Created by PhpStorm.
 * User: Bojidar
* Date: 10/7/2020
* Time: 5:50 PM
*/
use Illuminate\Support\Facades\Route;

Route::prefix('api')->namespace('\MicroweberPackages\User\Http\Controllers')->group(function () {
    Route::post('login', 'AuthController@login');
  //  Route::post('register', 'AuthController@register');
});

Route::prefix('api')->middleware('auth:api')->namespace('\MicroweberPackages\User\Http\Controllers')->group(function () {

    Route::get('/all-user', function() {
        return json_encode(['drenki_she_poluchish'=>true]);
    });

});