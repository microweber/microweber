<?php

namespace MicroweberPackages\Translation;


use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;

class TranslationImport
{
    public function import($translations)
    {
        if (is_array($translations)) {
            foreach ($translations as $translation) {

                $translationText = trim($translation['translation_text']);
                if (empty($translationText)) {
                    continue;
                }

                $getTranslationKey = TranslationKey::where(\DB::raw('md5(translation_key)'), md5($translation['translation_key']))
                    ->where('translation_namespace', $translation['translation_namespace'])
                    ->where('translation_group', $translation['translation_group'])
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
                    ->first();

                // Save new translation text
                if ($getTranslationText == null) {
                    $getTranslationText = new TranslationText();
                    $getTranslationText->translation_key_id = $getTranslationKey->id;
                    $getTranslationText->translation_locale = $translation['translation_locale'];
                }

                $getTranslationText->translation_text = $translationText;
                $getTranslationText->save();
            }
            \Cache::tags('translation_keys')->flush();

            return ['success'=> 'Importing language file success.'];
        }

        return ['error'=> 'Can\'t import this language file.'];
    }
}