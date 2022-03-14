<?php

\Route::name('api.')

    ->prefix('api')
    ->middleware(['api', 'admin', 'xss'])
    ->group(function () {

        \Route::post('save_option', function () {
            return save_option(request()->all());
        });

    });
