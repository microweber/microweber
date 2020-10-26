<?php
/**
* Created by PhpStorm.
 * User: Bojidar
* Date: 10/7/2020
* Time: 5:50 PM
*/

use Illuminate\Support\Facades\Route;

Route::name('api.user.')->prefix('api/user')->middleware(['public.api'])->namespace('\MicroweberPackages\User\Http\Controllers')->group(function () {
    Route::post('login', 'AuthController@login')->name('login')->middleware(['allowed_ips']);
    Route::post('logout', 'AuthController@logout')->name('logout');
    Route::post('register', 'UserRegisterController@register')->name('register')->middleware(['allowed_ips']);
});

Route::name('api.')
    ->prefix('api')
    ->middleware(['api'])
    ->namespace('\MicroweberPackages\User\Http\Controllers\Api')
    ->group(function () {
        Route::apiResource('user', 'UserApiController');
    });

