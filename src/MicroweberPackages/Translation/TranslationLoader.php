<?php

namespace MicroweberPackages\Translation;

use Illuminate\Translation\FileLoader;
use MicroweberPackages\Translation\Models\Translation;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationKeyCached;
use MicroweberPackages\Translation\Models\TranslationText;

class TranslationLoader extends FileLoader
{

    public $translatedLanguageLines = [];
    public $allTranslationsCached = [];

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
        if ($this->allTranslationsCached) {
            // return cached array
         //   return $this->allTranslationsCached;
        }

        // Load translations from files
        $fileTranslations = parent::load($locale, $group, $namespace);
        if (!is_null($fileTranslations)) {
            $this->allTranslationsCached = $fileTranslations;
        }

        // Load translations from database
        if (mw_is_installed()) {

//            $getTranslations = \Cache::tags('translation_keys')->remember('translation_keys-'.$locale, 99999,function() use($locale,$namespace,$group){
//                 return TranslationKeyCached::where('translation_group', $group)
//                    ->join('translation_texts', 'translation_keys.id', '=', 'translation_texts.translation_key_id')
//                    ->where('translation_texts.translation_locale', $locale)
//                    ->where('translation_namespace', $namespace)
//                    ->get();
//            });


            $getTranslations = TranslationKeyCached::where('translation_group', $group)
                ->join('translation_texts', 'translation_keys.id', '=', 'translation_texts.translation_key_id')
                ->where('translation_texts.translation_locale', $locale)
                ->where('translation_namespace', $namespace)
                ->get();

             if ($getTranslations !== null) {
                foreach ($getTranslations as $translation) {
                    $translationText = $translation->translation_text;
                    //   $translationText = str_ireplace('{{app_name}}', 'Microweber', $translationText);
                    $this->allTranslationsCached[$translation->translation_key] = $translationText;
                }
            }
        }

        return $this->allTranslationsCached;
    }

    /*
    private function loadLanguageFiles($locale, $group, $namespace)
    {
        if (isset($this->translatedLanguageLines[$locale])) {
            return $this->translatedLanguageLines[$locale];
        }

        $languageFiles = [];
        $languageFiles[] = userfiles_path() . 'language' . DIRECTORY_SEPARATOR . $locale . '.json';

        if (empty($locale) || $locale == 'en') {
            $languageFiles[] = mw_includes_path() . 'language' . DIRECTORY_SEPARATOR . 'en.json';
        } else {
            $languageFiles[] = normalize_path(mw_includes_path() . 'language' . DIRECTORY_SEPARATOR . $locale . '.json', false);
        }

        foreach ($languageFiles as $languageFile) {
            if (is_file($languageFile)) {
                $languageContent = file_get_contents($languageFile);
                $languageVariables = json_decode($languageContent, true);
                if (isset($languageVariables) and is_array($languageVariables)) {
                    foreach ($languageVariables as $languageVariableKey => $languageVariableValue) {
                        $this->translatedLanguageLines[$locale][$languageVariableKey] = $languageVariableValue;
                    }
                }
            }
        }

        return $this->translatedLanguageLines[$locale];
    }*/

}
