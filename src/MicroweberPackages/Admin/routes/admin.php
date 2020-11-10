<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/10/2020
 * Time: 4:48 PM
 */



Route::name('admin.')
    ->prefix('admin')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Admin\Http\Controllers')
    ->group(function () {
        Route::get('notification', 'NotificationController@index')->name('notification.index');
    });