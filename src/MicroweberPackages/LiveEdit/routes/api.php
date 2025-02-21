<?php

use \Illuminate\Support\Facades\Route;


Route::name('api.live-edit.')
    ->prefix('api/live-edit')
    ->middleware(['web', 'admin', 'live_edit'])
    ->group(function () {


        Route::get('get-top-right-menu', MicroweberPackages\LiveEdit\Http\Controllers\Api\LiveEditMenusApi::class . '@getTopRightMenu')
            ->name('get-top-right-menu');

    });
