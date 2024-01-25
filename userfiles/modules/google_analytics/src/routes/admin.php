<?php

Route::name('admin.google_analytics.')
    ->prefix(ADMIN_PREFIX . '/google-analytics')
    ->middleware(['admin'])
    ->namespace('MicroweberPackages\Modules\GoogleAnalytics\Http\Controllers\Admin')
    ->group(function () {

       Route::get('/', 'AdminGoogleAnalyticsController@index')->name('index');

    });
