<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/13/2020
 * Time: 12:13 PM
 */


Route::name('api.')
    ->prefix('api')
 ->middleware(['api','xss'])
    ->namespace('\MicroweberPackages\Form\Http\Controllers\ApiPublic')
    ->group(function () {
        Route::post('post_form', 'FormController@post')->name('post.form')->middleware(['throttle:60,1']);
    });
