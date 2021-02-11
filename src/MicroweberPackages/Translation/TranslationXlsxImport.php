<?php

namespace MicroweberPackages\Translation;


use MicroweberPackages\Backup\Readers\XlsxReader;
use MicroweberPackages\Translation\Models\Translation;

class TranslationXlsxImport
{
    public function import($file)
    {
        $readFile = new XlsxReader($file);
        $data = $readFile->readData();

        if (isset($data['content'])) {

            foreach ($data['content'] as $translation) {

                $getTranslation = Translation::
                where(\DB::raw('md5(translation_key)'), md5($translation['translation_key']))
                    ->where('translation_namespace', $translation['translation_namespace'])
                    ->where('translation_group', $translation['translation_group'])
                    ->where('translation_locale', $translation['translation_locale'])
                    ->first();

                if ($getTranslation == null) {
                    $getTranslation = new Translation();
                    $getTranslation->translation_key = $translation['translation_key'];
                    $getTranslation->translation_namespace = $translation['translation_namespace'];
                    $getTranslation->translation_group = $translation['translation_group'];
                    $getTranslation->translation_locale = $translation['translation_locale'];
                }

                $getTranslation->translation_text = $translation['translation_text'];
                $getTranslation->save();
            }


            return ['success'=> _e('Importing language file success.', true)];
        }

        return ['error'=> _e('Can\'t import this language file.', true)];
    }
}