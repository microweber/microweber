<?php

namespace MicroweberPackages\Translation;


class Translator extends \Illuminate\Translation\Translator
{
    public static $newTexts = [];

    /**
     * Get the translation for the given key.
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @param  bool  $fallback
     * @return string|array
     */
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        $response = parent::get($key, $replace, $locale, $fallback);
        if (!empty($response)) {
            return $response;
        }

        self::$newTexts[] = $key;

        return $response;
    }

    public function getNewTexts()
    {
        return self::$newTexts;
    }
}
