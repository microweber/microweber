<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/16/2021
 * Time: 1:58 PM
 */

Route::name('admin.')
    ->prefix(mw_admin_prefix_url())
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Marketplace\Http\Controllers\Admin')
    ->group(function () {

        Route::get('marketplace', 'MarketplaceController@index')->name('marketplace.index');

    });
