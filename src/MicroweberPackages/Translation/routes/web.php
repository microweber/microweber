<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/2/2021
 * Time: 2:47 PM
 */

Route::post('admin/language/save', '\MicroweberPackages\Translation\Http\Controllers\TranslationController@save')->name('admin.language.save');
Route::post('admin/language/import', '\MicroweberPackages\Translation\Http\Controllers\TranslationController@import')->name('admin.language.import');
Route::post('admin/language/export', '\MicroweberPackages\Translation\Http\Controllers\TranslationController@export')->name('admin.language.export');