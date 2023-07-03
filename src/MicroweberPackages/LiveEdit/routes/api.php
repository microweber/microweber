<?php

use Illuminate\Support\Facades\Route;

Route::name('api.live-edit.')
    ->prefix('api/live-edit')
    ->middleware(['api', 'admin'])
    ->group(function () {

        Route::namespace('MicroweberPackages\LiveEdit\Http\Controllers\Api')->group(function () {
            Route::get('get-top-right-menu', 'LiveEditMenusApi@getTopRightMenu')->name('get-top-right-menu');
        });

    });
