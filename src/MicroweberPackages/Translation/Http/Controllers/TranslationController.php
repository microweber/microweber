<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/2/2021
 * Time: 2:51 PM
 */

namespace MicroweberPackages\Translation\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Backup\Exporters\JsonExport;
use MicroweberPackages\Backup\Exporters\XlsxExport;
use MicroweberPackages\Translation\Models\Translation;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\TranslationXlsxImport;

class TranslationController {

    public function sendToUs()
    {


    }

    public function import(Request $request) {

        $src = $request->post('src');
        $file = url2dir($src);

        $import = new TranslationXlsxImport();
        return $import->import($file);

    }

    public function export(Request $request) {

        $namespace = $request->post('namespace','*');
        $locale = $request->post('locale', mw()->lang_helper->default_lang());
        $format = $request->post('format', 'json');


        $exportFileName = 'translation-global';
        if ($namespace !== '*') {
            $exportFileName = 'translation-' . $namespace;
        }
        $exportFileName = $exportFileName . '-' . $locale;

        $getTranslations = [];
        $getTranslationsQuery = TranslationKey::
            join('translation_texts', 'translation_keys.id', '=', 'translation_texts.translation_key_id')
            ->where('translation_texts.translation_locale', $locale)
            ->where('translation_namespace', $namespace)
            ->get();
        if ($getTranslationsQuery !== null) {
            $getTranslations = $getTranslationsQuery->toArray();
        }

        $getTranslationsWithoutTexts = TranslationKey::
            whereNotIn('translation_keys.id', function ($query) use($locale) {
                $query->select('translation_texts.translation_key_id')->from('translation_texts')->where('translation_texts.translation_locale', $locale);
            })
            ->get();

        if ($getTranslationsWithoutTexts !== null) {
            $getTranslations = array_merge($getTranslations, $getTranslationsWithoutTexts->toArray());
        }

        $readyTranslations = [];

        foreach ($getTranslations as $translation) {

            if (!isset($translation['translation_text'])) {
                $translation['translation_text'] = '';
                $translation['translation_locale'] = '';
            }

            $readyTranslations[] = [
              'translation_group'=>$translation['translation_group'],
              'translation_namespace'=>$translation['translation_namespace'],
              'translation_key'=>$translation['translation_key'],
              'translation_text'=>$translation['translation_text'],
              'translation_locale'=>$translation['translation_locale'],
            ];
        }

        $exportFileName = $exportFileName . '-' . date('Y-m-d-H-i-s');

        if ($format == 'json') {

            $export = new JsonExport($readyTranslations);
            $export->setFilename($exportFileName);
            $export->useEncodeFix = false;

            return $export->start();

        } else {
            $export = new XlsxExport();
            $export->data[$exportFileName] = $readyTranslations;

            return $export->start();
        }

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