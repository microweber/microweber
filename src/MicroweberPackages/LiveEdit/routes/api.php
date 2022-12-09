<?php

use Illuminate\Support\Facades\Route;

\Route::name('api.live_edit.')

    ->prefix('api/live-edit')
    ->middleware(['api', 'admin', 'xss'])
    ->namespace('\MicroweberPackages\LiveEdit\Http\Controllers\Api')
    ->group(function () {
        Route::get('modules-list', 'ModulesList@index');
    });
