<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/5/2020
 * Time: 1:45 PM
 */

use Illuminate\Support\Facades\Route;

// Admin web
Route::prefix('admin')->middleware(['admin'])->namespace('\MicroweberPackages\User\Http\Controllers')->group(function () {
    Route::get('login', 'UserLoginController@index')->name('admin.login')->middleware(['allowed_ips']);
});

// Public user
Route::name('user.')->namespace('\MicroweberPackages\User\Http\Controllers')->group(function () {

    Route::get('login', 'UserLoginController@loginForm')->name('login');

});

Route::namespace('\MicroweberPackages\User\Http\Controllers')->group(function () {
    Route::get('email/verify/{id}/{hash}', 'UserVerifyController@verify')->name('verification.verify');
});