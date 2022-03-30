<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/16/2021
 * Time: 1:58 PM
 */

Route::name('admin.')
    ->prefix('admin')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Order\Http\Controllers\Admin')
    ->group(function () {
        Route::get('order/abandoned', 'OrderController@abandoned')->name('order.abandoned');
        Route::resource('order', 'OrderController',['only'=>['index','show']]);
    });
