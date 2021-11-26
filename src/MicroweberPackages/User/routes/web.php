<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/5/2020
 * Time: 1:45 PM
 */

use Illuminate\Support\Facades\Route;


// Public user

// route moved to src/MicroweberPackages/App/routes/web.php  because if bug
// Route::get('login', '\MicroweberPackages\User\Http\Controllers\UserLoginController@loginForm')->name('login');


Route::namespace('\MicroweberPackages\User\Http\Controllers')->middleware(['web'])->group(function () {

    Route::get('email/verify/{id}/{hash}', 'UserVerifyController@verify')->name('verification.verify')
        ->middleware([\MicroweberPackages\User\Http\Middleware\UserValidateEmailSignature::class]);

    Route::get('email/verify-resend/{id}/{hash}', 'UserVerifyController@showResendForm')->name('verification.resend');
    Route::post('email/verify-resend/{id}/{hash}', 'UserVerifyController@sendVerifyEmail')->name('verification.send');

    Route::get('/forgot-password', 'UserForgotPasswordController@showForgotForm')->name('password.request');
    Route::post('/forgot-password', 'UserForgotPasswordController@send')->name('password.email');

    Route::get('/reset-password/{token}', 'UserForgotPasswordController@showResetForm')->name('password.reset');
    Route::post('/reset-password', 'UserForgotPasswordController@update')->name('password.update');
});


