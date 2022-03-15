<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/2/2021
 * Time: 2:51 PM
 */

namespace MicroweberPackages\Translation\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Backup\Readers\XlsxReader;
use MicroweberPackages\Export\Formats\JsonExport;
use MicroweberPackages\Export\Formats\XlsxExport;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;
use MicroweberPackages\Translation\TranslationImport;

class TranslationController {

    public function sendToUs()
    {


    }

    public function import(Request $request) {

        $src = $request->post('src');
        $file = url2dir($src);

        $readFile = new XlsxReader($file);
        $data = $readFile->readData();
        $translations = $data['content'];

        $import = new TranslationImport();
        $replace_values = intval($request->post('replace_values'));

        $import->replaceTexts($replace_values);

        return $import->import($translations);

    }

    public function export(Request $request) {

        $namespace = $request->post('namespace','*');
        $locale = $request->post('locale', mw()->lang_helper->default_lang());
        $format = $request->post('format', 'json');

        if (!is_lang_correct($locale)) {
            return [];
        }

        $exportFileName = 'translation-global';

        $namespace = str_replace('..', '', $namespace);
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

        if (empty($getTranslations)) {
            return [];
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

               $getTranslationKey = TranslationKey::
                    where(\DB::raw('md5(translation_key)'), md5($translation['translation_key']))
                   ->where('translation_group', $translation['translation_group'])
                   ->where('translation_namespace', $translation['translation_namespace'])
                  ->first();

               if ($getTranslationKey == null) {
                   $getTranslationKey = new TranslationKey();
                   $getTranslationKey->translation_key = $translation['translation_key'];
                   $getTranslationKey->translation_namespace = $translation['translation_namespace'];
                   $getTranslationKey->translation_group = $translation['translation_group'];
               }
               $getTranslationKey->save();


               // Get translation text
               $getTranslationText = TranslationText::where('translation_key_id', $getTranslationKey->id)
                   ->where('translation_locale', $translation['translation_locale'])
                   ->get();

               if ($getTranslationText->count() > 1) {
                   foreach($getTranslationText as $dublicatedText) {
                       $dublicatedText->delete();
                   }
               }

               $getTranslationText = TranslationText::where('translation_key_id', $getTranslationKey->id)
                   ->where('translation_locale', $translation['translation_locale'])
                   ->first();

               // Save new translation text
               if ($getTranslationText == null) {
                   $getTranslationText = new TranslationText();
                   $getTranslationText->translation_key_id = $getTranslationKey->id;
                   $getTranslationText->translation_locale = $translation['translation_locale'];
               }

               $getTranslationText->translation_text = trim($translation['translation_text']);
               $getTranslationText->save();

           }
       }
        \Cache::tags('translation_keys')->flush();

    }


}
