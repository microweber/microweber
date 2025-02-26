<?php

use Illuminate\Support\Facades\Route;
use Modules\Multilanguage\Http\Controllers\MultilanguageController;
use Modules\Multilanguage\Http\Controllers\MultilanguageApiController;

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

Route::post('/multilanguage/geolocaiton_test', [MultilanguageApiController::class, 'geolocaitonTest']);

//Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//    Route::apiResource('multilanguage', MultilanguageController::class)->names('multilanguage');
//});
