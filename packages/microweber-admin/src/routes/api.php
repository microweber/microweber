<?php

use  \Illuminate\Support\Facades\Route;
 
Route::name('api.')

    ->prefix('api')
    ->middleware(['api', 'admin', 'xss'])
    ->group(function () {

        Route::get('mw_delete_license', function ()  {
            $update_api = mw('update');
            $params = request()->all();

            return $update_api->delete_license($params);
        })->name('mw_delete_license');

        Route::post('mw_validate_licenses', function ()  {
            $update_api = mw('update');
            $params = request()->all();

            return $update_api->validate_license($params);
        })->name('mw_validate_licenses');

        Route::post('mw_consume_license', function () {

            $update_api = mw('update');
            $params = request()->all();

            return $update_api->consume_license($params);
        })->name('mw_consume_license');

        Route::post('mw_save_license', function () {
            $params = request()->all();
            $update_api = mw('update');

            return $update_api->save_license($params);
        })->name('mw_save_license');



    });
