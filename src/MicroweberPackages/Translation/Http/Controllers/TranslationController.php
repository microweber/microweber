<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/2/2021
 * Time: 2:51 PM
 */

namespace MicroweberPackages\Translation\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Backup\Exporters\XlsxExport;
use MicroweberPackages\Translation\Models\Translation;

class TranslationController {

    public function import(Request $request) {

    }

    public function export(Request $request) {
        
        $exportFileName = 'translation-global';

        $query = Translation::query();
        $namespace = $request->post('namespace');

        if (!empty($namespace)) {
            $query->where('translation_namespace', $namespace);
            if ($namespace !== '*') {
                $exportFileName = 'translation-' . $namespace;
            }
        }

        $data = $query->get()->toArray();

        $export = new XlsxExport();
        $export->data[$exportFileName] = $data;

        return $export->start();

    }

    public function save(Request $request) {

       $translations = base64_decode($request->post('translations'));
       $translations = json_decode($translations, true);

       $saveTranslations = [];

       foreach ($translations['translations'] as $translationLocales) {
           foreach ($translationLocales as $translationLocale=>$translation) {
               $translation['translation_locale'] = $translationLocale;
               $saveTranslations[md5($translation['translation_key'].$translation['translation_locale'].$translation['translation_group'].$translation['translation_namespace'])] = $translation;
           }
       }

       \Config::set('microweber.disable_model_cache', true);

       if (!empty($saveTranslations)) {
           foreach($saveTranslations as $translation) {

               $findTranslataion = Translation::
                    where(\DB::raw('md5(translation_key)'), md5($translation['translation_key']))
                   ->where('translation_locale', $translation['translation_locale'])
                   ->where('translation_group', $translation['translation_group'])
                   ->where('translation_namespace', $translation['translation_namespace'])
                  ->first();

               if ($findTranslataion == null) {
                   $findTranslataion = new Translation();
                   $findTranslataion->translation_locale = $translation['translation_locale'];
                   $findTranslataion->translation_key = $translation['translation_key'];
                   $findTranslataion->translation_namespace = $translation['translation_namespace'];
                   $findTranslataion->translation_group = $translation['translation_group'];
               }

               $findTranslataion->translation_text = trim($translation['translation_text']);
               $findTranslataion->save();

               // Delete dublicates if exists
               Translation::where(\DB::raw('md5(translation_key)'), md5($translation['translation_key']))
                   ->where('translation_locale', $translation['translation_locale'])
                   ->where('translation_group', $translation['translation_group'])
                   ->where('translation_namespace', $translation['translation_namespace'])
                   ->where('id','!=', $findTranslataion->id)
                   ->delete();

           }
       }

    }

}