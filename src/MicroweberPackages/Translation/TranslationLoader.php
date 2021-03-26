<?php

namespace MicroweberPackages\Translation;

use Illuminate\Translation\FileLoader;
use MicroweberPackages\Translation\Models\Translation;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationKeyCached;
use MicroweberPackages\Translation\Models\TranslationText;

class TranslationLoader extends FileLoader
{


    /**
     * Load the messages for the given locale.
     *
     * @param string $locale
     * @param string $group
     * @param string $namespace
     *
     * @return array
     */
    public function load($locale, $group, $namespace = null): array
    {
        $translations  = [];

        // Load translations from files
        $fileTranslations = parent::load($locale, $group, $namespace);
        if (is_array($fileTranslations) and !empty($fileTranslations)) {
            $translations  = $fileTranslations;
        }

        // Load translations from database
        if (mw_is_installed()) {
             $getTranslations = TranslationKeyCached::where('translation_group', $group)
                ->join('translation_texts', 'translation_keys.id', '=', 'translation_texts.translation_key_id')
                ->where('translation_texts.translation_locale', $locale)
                ->where('translation_namespace', $namespace)
                ->get();

             if ($getTranslations !== null) {
                foreach ($getTranslations as $translation) {
                    $translationText = $translation->translation_text;
                    if($translationText){
                        $translations[$translation->translation_key] = $translationText;

                    }
                }
            }
        }

        return $translations;
    }

}
