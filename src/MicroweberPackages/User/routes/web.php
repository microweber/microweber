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


Route::name('admin.')
    ->prefix(ADMIN_PREFIX)
    ->middleware([
        'admin',
       // \MicroweberPackages\App\Http\Middleware\VerifyCsrfToken::class,
        \MicroweberPackages\App\Http\Middleware\XSS::class
    ])
    ->namespace('\MicroweberPackages\User\Http\Controllers\Admin')
    ->group(function () {
        Route::resource('user', 'UserController',['except' => ['show','edit', 'create']]);
    });

Route::namespace('\MicroweberPackages\User\Http\Controllers')->middleware(['web'])->group(function () {

    Route::get('/logout', 'UserLogoutController@index')->name('logout');
    Route::post('/logout', 'UserLogoutController@submit')->name('logout.submit');


    Route::get('email/verify/{id}/{hash}', 'UserVerifyController@verify')->name('verification.verify')
        ->middleware([\MicroweberPackages\User\Http\Middleware\UserValidateEmailSignature::class]);

    Route::get('email/verify-resend/{id}/{hash}', 'UserVerifyController@showResendForm')->name('verification.resend');
    Route::post('email/verify-resend/{id}/{hash}', 'UserVerifyController@sendVerifyEmail')->name('verification.send');

    Route::get('/forgot-password', 'UserForgotPasswordController@showForgotForm')->name('password.request');
    Route::post('/forgot-password', 'UserForgotPasswordController@send')
        ->middleware(['throttle:3,1',\MicroweberPackages\App\Http\Middleware\SameSiteRefererMiddleware::class])
        ->name('password.email');

    Route::get('/reset-password/{token}', 'UserForgotPasswordController@showResetForm')->name('password.reset');
    Route::post('/reset-password', 'UserForgotPasswordController@update')
        ->middleware(['throttle:3,1',\MicroweberPackages\App\Http\Middleware\SameSiteRefererMiddleware::class])
        ->name('password.update');
});


