<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::name('admin.')
    ->prefix('admin')
    ->namespace('\MicroweberPackages\Page\Http\Controllers\Admin')
    ->middleware(['XSS'])
    ->group(function () {

        Route::resource('pages', 'PagesController');
        
});