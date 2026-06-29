<?php

namespace MicroweberPackages\Translation;

class Translator extends \Illuminate\Translation\Translator
{
    /**
     * Track new keys encountered during translation that are not yet in the DB.
     */
    public $newKeys = [];

    public function clearNewKeys()
    {
        $this->newKeys = [];
    }

    /**
     * Get the translation for the given key.
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @param bool $fallback
     * @return string|array
     */
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        $locale = $locale ?: $this->locale;

        // For JSON translations, there is only one file per locale, so we will simply load
        // that file and then we will be ready to check the array for the key.
        $this->load('*', '*', $locale);

        $line = $this->loaded['*']['*'][$locale][$key] ?? null;

        // If we can't find a translation for the JSON key, we will attempt to translate it
        // using the typical translation file.
        if (!isset($line)) {
            [$namespace, $group, $item] = $this->parseKey($key);

            // If laravel cannot parse the group
            if ($group == $key) {
                $group = '*';
                $item = $key;
            }

            if (empty(trim($item))) {
                return;
            }

            if ($namespace and is_string($namespace) and $namespace != '*') {
                $this->load($namespace, $group, $locale);
                $line2 = $this->loaded[$namespace][$group][$locale][$key] ?? null;
                if ($line2) {
                    $item = $line2;
                }
            }

            if (empty($item)) {
                $this->newKeys[md5('**' . $key)] = [
                    'translation_namespace' => '*',
                    'translation_group' => '*',
                    'translation_key' => $key
                ];
            }

            $locales = $fallback ? $this->localeArray($locale) : [$locale];

            $foundedLine = false;
            foreach ($locales as $locale) {
                if (!is_null($line = $this->getLine(
                    $namespace, $group, $locale, $item, $replace
                ))) {
                    $foundedLine = $line;
                    break;
                }
            }

            if ($foundedLine) {
                return $foundedLine;
            } else {
                $this->newKeys[md5($namespace . $group . $item)] = [
                    'translation_namespace' => $namespace,
                    'translation_group' => $group,
                    'translation_key' => $item
                ];
            }
        }

        return $this->makeReplacements($line ?: $key, $replace);
    }

    public function getNewKeys()
    {
        return $this->newKeys;
    }
}