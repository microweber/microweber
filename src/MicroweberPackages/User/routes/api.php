<?php
/**
* Created by PhpStorm.
 * User: Bojidar
* Date: 10/7/2020
* Time: 5:50 PM
*/

use Illuminate\Support\Facades\Route;

/**
 * @deprecated
 */
/*Route::name('api.deprecated')->prefix('api')->namespace('\MicroweberPackages\User\Http\Controllers')->group(function () {
    Route::post('user_register', 'AuthController@register');
});*/
Route::get('user/login', function(){

    echo 'login public';

})->name('user.login');


Route::name('api.user.')->prefix('api/user')->middleware(['public.api'])->namespace('\MicroweberPackages\User\Http\Controllers')->group(function () {

    Route::post('login', 'AuthController@login')->name('login');
    Route::post('register', 'AuthController@register')->name('register');

});

Route::name('api.user.')->prefix('api/user')->middleware(['api'])->namespace('\MicroweberPackages\User\Http\Controllers')->group(function () {

    Route::get('all', function() {
        return json_encode(['drenki_she_poluchish'=>true]);
    });

});