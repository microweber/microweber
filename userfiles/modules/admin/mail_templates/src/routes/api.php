<?php

\Route::name('api.')

    ->prefix('api')
    ->middleware(['api', 'admin', 'xss'])
    ->group(function () {


        \Route::post('save_mail_template', function () {
            return save_mail_template(request()->all());
        })->name('save_mail_template');

        \Route::post('delete_mail_template', function () {
            return delete_mail_template(request()->all());
        })->name('delete_mail_template');

    });
