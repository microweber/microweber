<?php

Route::name('admin.')
    ->prefix(ADMIN_PREFIX)
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Multilanguage\Http\Controllers\Admin')
    ->group(function () {
        Route::get('multilanguage', 'MultilanguageController@index')->name('multilanguage.index');
    });
