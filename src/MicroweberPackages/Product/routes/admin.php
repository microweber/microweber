<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::name('admin.')
    ->prefix('admin')
    ->namespace('\MicroweberPackages\Product\Http\Controllers\Admin')
    ->middleware(['XSS'])
    ->group(function () {

        Route::resource('products', 'RoductsController');
        
});