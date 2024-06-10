<?php

Route::name('admin.google_analytics.')
    ->prefix(mw_admin_prefix_url_legacy() . '/google-analytics')
    ->middleware(['admin'])
    ->namespace('MicroweberPackages\Modules\GoogleAnalytics\Http\Controllers\Admin')
    ->group(function () {

       Route::get('/', 'AdminGoogleAnalyticsController@index')->name('index');

    });
