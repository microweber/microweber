<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/2/2021
 * Time: 2:47 PM
 */

Route::name('admin.')
    ->prefix('admin')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Translation\Http\Controllers')
    ->group(function () {

        Route::post('language/save', 'TranslationController@save')->name('language.save');
        Route::post('language/import', 'TranslationController@import')->name('language.import');
        Route::post('language/export', 'TranslationController@export')->name('language.export');
        Route::post('language/send_to_us', 'TranslationController@sendToUs')->name('language.send_to_us');

});