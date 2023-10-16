<?php


\Route::name('api.')

    ->prefix('api')
    ->middleware(['api', 'admin', 'xss'])
    ->group(function () {

        \Route::post('mw_delete_license', function ()  {
            $update_api = mw('update');
            $params = request()->all();

            return $update_api->delete_license($params);
        });

        \Route::post('mw_validate_licenses', function ()  {
            $update_api = mw('update');
            $params = request()->all();

            return $update_api->validate_license($params);
        });

        \Route::post('mw_consume_license', function () {

            $update_api = mw('update');
            $params = request()->all();

            return $update_api->consume_license($params);
        });

        \Route::post('mw_save_license', function () {
            $params = request()->all();
            $update_api = mw('update');

            return $update_api->save_license($params);
        });

        \Route::post('save_mail_template', function () {
            return save_mail_template(request()->all());
        })->name('save_mail_template');

        \Route::post('delete_mail_template', function () {
            return delete_mail_template(request()->all());
        })->name('delete_mail_template');

    });
