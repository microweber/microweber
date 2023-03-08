<?php


Route::name('admin.')
    ->prefix(mw_admin_prefix_url())
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Shop\Http\Controllers\Admin')
    ->group(function () {
        Route::get('shop/dashboard', 'DashboardShopController@dashboard')->name('shop.dashboard');
    });

Route::name('admin.shop.filter.')
    ->prefix(mw_admin_prefix_url(). '/shop/filter')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Shop\Http\Controllers')
    ->group(function () {

        Route::post('get-custom-fields-table', 'LiveEditAdminController@getCustomFieldsTableFromPage')->name('get-custom-fields-table');

    });


