<?php

use Illuminate\Support\Facades\Route;

\Route::name('api.')

    ->prefix('api')
    ->middleware(['api', 'admin', 'xss'])
    ->namespace('\MicroweberPackages\LiveEdit\Http\Controllers\Api')
    ->group(function () {




    });
