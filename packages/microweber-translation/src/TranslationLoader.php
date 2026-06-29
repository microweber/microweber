<?php

namespace MicroweberPackages\Translation;

use Illuminate\Translation\FileLoader;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;

class TranslationLoader extends FileLoader
{
    /**
     * Load the messages for the given locale.
     *
     * @param string $locale
     * @param string $group
     * @param string|null $namespace
     *
     * @return array
     */
    public function load($locale, $group, $namespace = null): array
    {
        $translations = [];

        if ($namespace != '*') {
            $fileTranslations = parent::loadNamespaced($locale, $group, $namespace);
        } else {
            $fileTranslations = parent::load($locale, $group, $namespace);
        }

        if (is_array($fileTranslations) and !empty($fileTranslations)) {
            $translations = $fileTranslations;
        }

        // Load translations from database
        try {
            $getTranslations = $this->loadFromDatabase($locale, $group, $namespace);
            if ($getTranslations !== null and !empty($getTranslations)) {
                foreach ($getTranslations as $translation) {
                    if (isset($translation['translation_key']) and isset($translation['translation_text']) and $translation['translation_text']) {
                        $translations[$translation['translation_key']] = $translation['translation_text'];
                    }
                }
            }
        } catch (\Exception $e) {
            // Database might not be available (standalone usage without DB)
        }

        return $translations;
    }

    /**
     * Load translations from database.
     */
    protected function loadFromDatabase(string $locale, string $group, ?string $namespace): array
    {
        try {
            $query = TranslationKey::query()
                ->select(['translation_text', 'translation_key'])
                ->where('translation_group', $group)
                ->join('translation_texts', 'translation_keys.id', '=', 'translation_texts.translation_key_id')
                ->where('translation_texts.translation_locale', $locale)
                ->where('translation_namespace', $namespace);

            $results = $query->get();

            if ($results && $results->isNotEmpty()) {
                return $results->toArray();
            }
        } catch (\Exception $e) {
            // Table might not exist yet
        }

        return [];
    }
}