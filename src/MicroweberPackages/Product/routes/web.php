<?php

Route::name('product')->prefix('product')
    ->namespace('\MicroweberPackages\Product\Http\Controllers')
    ->group(function () {
        Route::get('quick-view', 'ProductQuickViewController@view')->name('quick-view');
    });

