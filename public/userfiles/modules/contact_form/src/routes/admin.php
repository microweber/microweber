<?php

use Illuminate\Support\Facades\Route;

Route::name('admin.contact-form.')
    ->prefix(mw_admin_prefix_url_legacy() . '/contact-form')
    ->middleware(['admin'])
    ->namespace('MicroweberPackages\Modules\ContactForm\Http\Controllers\Admin')
    ->group(function () {

        Route::get('/', 'AdminController@index')->name('index');

    });
