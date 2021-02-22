<?php

namespace MicroweberPackages\Translation;


use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;

class TranslationImport
{
    public $logger = null;

    public function import($translations)
    {
        if (is_array($translations)) {
            foreach ($translations as $translation) {

                if (!isset($translation['translation_text'])) {
                    continue;
                }
                if (!isset($translation['translation_locale'])) {
                    continue;
                }
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
                    $getTranslationKey->save();
                    $this->log("Imported translation key " . $getTranslationKey->id);

                }

                // Get translation text
                $getTranslationText = TranslationText::where('translation_key_id', $getTranslationKey->id)
                    ->where('translation_locale', $translation['translation_locale'])
                    ->first();

                // Save new translation text
                if ($getTranslationText == null) {
                    $this->log("Importing translation text for key " . $getTranslationKey->id);
                    $getTranslationText = new TranslationText();
                    $getTranslationText->translation_key_id = $getTranslationKey->id;
                    $getTranslationText->translation_locale = $translation['translation_locale'];
                    $getTranslationText->translation_text = $translationText;
                    $getTranslationText->save();
                }


            }
            \Cache::tags('translation_keys')->flush();

            return ['success' => 'Importing language file success.'];
        }

        return ['error' => 'Can\'t import this language file.'];
    }

    public function log($text)
    {
        if (is_object($this->logger) and method_exists($this->logger, 'log')) {
            $this->logger->log($text);
        }
    }



}