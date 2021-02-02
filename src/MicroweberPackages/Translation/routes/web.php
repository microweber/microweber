<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/2/2021
 * Time: 2:47 PM
 */

Route::post('admin/language/save', '\MicroweberPackages\Translation\Http\Controllers\TranslationController@save')->name('admin.language.save');