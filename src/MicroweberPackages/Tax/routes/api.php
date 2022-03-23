<?php

use Illuminate\Support\Facades\Route;

\Route::name('api.')

    ->prefix('api')
    ->middleware(['api', 'admin', 'xss'])
    ->namespace('\MicroweberPackages\Tax\Http\Controllers\Api')
    ->group(function () {

        Route::any('shop/save_tax_item', function () {
            $data = request()->all();
            return mw()->tax_manager->save($data);
        });

        Route::any('shop/delete_tax_item', function () {
            $data = request()->all();
            return mw()->tax_manager->delete_by_id($data);
        });

    });
