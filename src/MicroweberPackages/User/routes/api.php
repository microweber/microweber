<?php
/**
* Created by PhpStorm.
 * User: Bojidar
* Date: 10/7/2020
* Time: 5:50 PM
*/

use Illuminate\Support\Facades\Route;

// OLD API SAVE USER
Route::post('api/save_user', function (Request $request) {
    if (!defined('MW_API_CALL')) {
        define('MW_API_CALL', true);
    }
    $input = Input::all();
    return save_user($input);
});


Route::name('api.user.')->prefix('api/user')->middleware(['public.api'])->namespace('\MicroweberPackages\User\Http\Controllers')->group(function () {

    Route::post('login', 'UserLoginController@login')->name('login')->middleware(['allowed_ips','throttle:60,1']);
    Route::post('logout', 'UserLoginController@logout')->name('logout');
    Route::post('register', 'UserRegisterController@register')->name('register')->middleware(['allowed_ips']);

    Route::post('/forgot-password', 'UserForgotPasswordController@send')->name('password.email');
    Route::post('/reset-password', 'UserForgotPasswordController@update')->name('password.update');

    Route::post('/profile-update', 'UserProfileController@update')->name('profile.update');

});

Route::name('api.')
    ->prefix('api')
    ->middleware(['api'])
    ->namespace('\MicroweberPackages\User\Http\Controllers\Api')
    ->group(function () {
        Route::apiResource('user', 'UserApiController');
    });
