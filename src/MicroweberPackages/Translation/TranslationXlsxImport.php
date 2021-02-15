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

                $getTranslation = TranslationKey::where(\DB::raw('md5(translation_key)'), md5($translation['translation_key']))
                    ->where('translation_namespace', $translation['translation_namespace'])
                    ->where('translation_group', $translation['translation_group'])
                    ->first();

                if ($getTranslation == null) {
                    $getTranslation = new TranslationKey();
                    $getTranslation->translation_key = $translation['translation_key'];
                    $getTranslation->translation_namespace = $translation['translation_namespace'];
                    $getTranslation->translation_group = $translation['translation_group'];
                }
                $getTranslation->save();

                // Get translation text
                $getTranslationText = TranslationText::where('translation_key_id', $getTranslation->id)
                    ->where('translation_locale', $translation['translation_locale'])
                    ->first();

                // Save new translation text
                if ($getTranslationText == null) {
                    $getTranslationText = new TranslationText();
                    $getTranslationText->translation_key_id = $getTranslation->id;
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