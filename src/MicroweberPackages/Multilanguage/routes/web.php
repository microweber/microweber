<?php

Route::name('admin.')
    ->prefix(mw_admin_prefix_url())
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Multilanguage\Http\Controllers\Admin')
    ->group(function () {
        Route::get('multilanguage', 'MultilanguageController@index')->name('multilanguage.index');
    });
