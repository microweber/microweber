<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/2/2021
 * Time: 2:47 PM
 */

Route::name('admin.')
    ->prefix(ADMIN_PREFIX)
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Translation\Http\Controllers')
    ->group(function () {

        Route::post('language/save', 'TranslationController@save')->name('language.save');
        Route::post('language/import', 'TranslationController@import')->name('language.import');
        Route::post('language/export', 'TranslationController@export')->name('language.export');
        Route::post('language/send_to_us', 'TranslationController@sendToUs')->name('language.send_to_us');

});
/*
Route::get('migrate_old_table', function () {

    $getTranslations = \DB::table('translations')
        ->groupBy(\DB::raw("MD5(translation_key)"))
        ->groupBy('translation_locale')
        ->get();

    if ($getTranslations !== null) {
        $readyTranslations = [];
        foreach ($getTranslations as $translation) {
            $readyTranslations[] = [
                'translation_locale' => $translation->translation_locale,
                'translation_key' => $translation->translation_key,
                'translation_text' => $translation->translation_text,
                'translation_namespace' => $translation->translation_namespace,
                'translation_group' => $translation->translation_group
            ];
        }

        $import = new \MicroweberPackages\Translation\TranslationImport();
        $import->import($readyTranslations);
    }
});*/
