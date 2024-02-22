<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/12/2020
 * Time: 2:36 PM
 */


Route::name('admin.import.')
    ->prefix(mw_admin_prefix_url().'/import')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Import\Http\Controllers\Admin')
    ->group(function () {


    });
