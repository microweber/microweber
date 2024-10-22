<?php

use Illuminate\Support\Facades\Cookie;
use  \Illuminate\Support\Facades\Route;

Route::name('api.module.')
    ->prefix('api/module')
    ->middleware(['api', 'admin'])
    ->group(function () {

        Route::namespace('MicroweberPackages\Module\Http\Controllers\Api')->group(function () {
            Route::get('list', \MicroweberPackages\Module\Http\Controllers\Api\ModulesApiLiveEdit::class . '@index')->name('list');  //api.module.list
            Route::get('getSkins', \MicroweberPackages\Module\Http\Controllers\Api\ModulesApiLiveEdit::class . '@getSkins')->name('getSkins'); //api.module.getSkins
        });
//        if (config('microweber.allow_php_files_upload')) {
//            Route::namespace('MicroweberPackages\Module\Http\Controllers\Api')->group(function () {
//                Route::post('upload', 'ModuleUploadController@upload')->name('upload');
//            });
//        }
    });


Route::name('api.')
    ->prefix('api/')
    ->middleware(['api', 'admin'])
    ->group(function () {

        Route::any('clearcache', function () {
            return clearcache();
        });

        Route::any('mw_post_update', function () {
            $status = mw_post_update();

            $cookie = Cookie::forget('XSRF-TOKEN');

            $response = response()->make('updated', 200)->withCookie($cookie);
            return $response;
        });

        Route::any('mw_reload_modules', function () {
            return mw_reload_modules();
        });

    });
