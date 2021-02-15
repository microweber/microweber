<?php

namespace MicroweberPackages\Translation;


use MicroweberPackages\Backup\Readers\XlsxReader;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;

class TranslationXlsxImport
{
    public function import($file)
    {
        set_time_limit(-0);

        $readFile = new XlsxReader($file);
        $data = $readFile->readData();

        if (isset($data['content'])) {
            foreach ($data['content'] as $translation) {

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

                $getTranslationText->translation_text = $translation['translation_text'];
                $getTranslationText->save();
            }

            return ['success'=> _e('Importing language file success.', true)];
        }

        return ['error'=> _e('Can\'t import this language file.', true)];
    }
}