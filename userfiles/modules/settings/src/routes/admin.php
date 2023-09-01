<?php

Route::prefix(mw_admin_prefix_url() . '/settings')
    ->namespace('\MicroweberPackages\Modules\Settings\Http\Controllers\Admin')
    ->middleware(['admin','api','xss'])->group(function () {

        Route::get('/', 'SettingsController@index')->name('admin.settings.index');

    });
