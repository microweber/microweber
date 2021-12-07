<?php

Route::name('admin.')
    ->prefix('admin')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Multilanguage\Http\Controllers\Admin')
    ->group(function () {
        Route::get('multilanguage', 'MultilanguageController@index')->name('multilanguage.index');
    });
