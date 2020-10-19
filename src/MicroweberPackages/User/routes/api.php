<?php
/**
* Created by PhpStorm.
 * User: Bojidar
* Date: 10/7/2020
* Time: 5:50 PM
*/
use Illuminate\Support\Facades\Route;

Route::name('api.')->prefix('api/user')->middleware(['throttle:6,1'])->namespace('\MicroweberPackages\User\Http\Controllers')->group(function () {
    Route::post('login', 'AuthController@login');
  //  Route::post('register', 'AuthController@register');
});


Route::name('api.')->prefix('api/user')->middleware(['auth:sanctum'])->namespace('\MicroweberPackages\User\Http\Controllers')->group(function () {

    Route::get('all-user', function() {
        return json_encode(['drenki_she_poluchish'=>true]);
    });

});