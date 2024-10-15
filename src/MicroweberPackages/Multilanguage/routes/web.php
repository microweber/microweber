<?php
use  \Illuminate\Support\Facades\Route;

Route::name('admin.')
    ->prefix(mw_admin_prefix_url_legacy())
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Multilanguage\Http\Controllers\Admin')
    ->group(function () {
        Route::get('multilanguage', 'MultilanguageController@index')->name('multilanguage.index');
    });
