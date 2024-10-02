<?php

use Illuminate\Support\Facades\Route;
use Modules\Form\Http\Controllers\FormController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::name('api.')
    ->prefix('api')
    ->middleware(['api','xss'])
    ->namespace('\Modules\Form\Http\Controllers\ApiPublic')
    ->group(function () {
        Route::post('post_form', 'FormController@post')->name('post.form')->middleware(['throttle:60,1']);
    });
