<?php

namespace MicroweberPackages\Translation;

use Illuminate\Translation\FileLoader;
use MicroweberPackages\App\Managers\Helpers\Lang;

class TranslationManager extends FileLoader
{

    /**
     * Load the messages for the given locale.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string|null  $namespace
     * @return array
     */
    public function load($locale, $group, $namespace = null)
    {
        $fileTranslations = parent::load($locale, $group, $namespace);

        if (! is_null($namespace) && $namespace !== '*') {
            return $fileTranslations;
        }

        $lines = $this->_loadLanguageFiles($locale, $group, $namespace);

        if (isset($lines[$locale][$group])) {
            return $lines[$locale][$group];
        }
    }

    private function _loadLanguageFiles($locale, $group, $namespace)
    {
        $languageFiles = [];
        $languageFiles[] = userfiles_path() . 'language' . DIRECTORY_SEPARATOR . $locale . '.json';

        if (empty($locale) || $locale == 'en') {
            $languageFiles[] = mw_includes_path() . 'language' . DIRECTORY_SEPARATOR . 'en.json';
        } else {
            $languageFiles[] =  normalize_path(mw_includes_path() . 'language' . DIRECTORY_SEPARATOR . $locale . '.json', false);
        }

        $variablesWithContent = [];
        foreach ($languageFiles as $languageFile) {
            if (is_file($languageFile)) {
                $languageContent = file_get_contents($languageFile);
                $languageVariables = json_decode($languageContent, true);
                if (isset($languageVariables) and is_array($languageVariables)) {
                    foreach ($languageVariables as $languageVariableKey=> $languageVariableValue) {
                        $variablesWithContent[$locale][$languageVariableKey] = $languageVariableValue;
                    }
                }
            }
        }

        return $variablesWithContent;
    }
}
