<?php

namespace MicroweberPackages\Translation;


use MicroweberPackages\Backup\Readers\XlsxReader;
use MicroweberPackages\Translation\Models\TranslationKey;

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
            }

            return ['success'=> _e('Importing language file success.', true)];
        }

        return ['error'=> _e('Can\'t import this language file.', true)];
    }
}