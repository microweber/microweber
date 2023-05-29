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
    ->namespace('\MicroweberPackages\Page\Http\Controllers\Admin')
    ->group(function () {
        Route::resource('page', 'PageController', ['except' => ['show']]);
        Route::get('page/design', 'PageController@design')->name('page.design');
    });
