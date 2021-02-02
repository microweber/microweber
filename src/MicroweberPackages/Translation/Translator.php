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
        $locale = $locale ?: $this->locale;

        // For JSON translations, there is only one file per locale, so we will simply load
        // that file and then we will be ready to check the array for the key. These are
        // only one level deep so we do not need to do any fancy searching through it.
        $this->load('*', '*', $locale);

        $line = $this->loaded['*']['*'][$locale][$key] ?? null;

        // If we can't find a translation for the JSON key, we will attempt to translate it
        // using the typical translation file. This way developers can always just use a
        // helper such as __ instead of having to pick between trans or __ with views.
        if (! isset($line)) {

            [$namespace, $group, $item] = $this->parseKey($key);

            if (empty($item)) {
               // echo 'This is without namespace, only key ->'.$key . '<br />';
                self::$newTexts[md5($key. $locale)] = [
                    'namespace' => '*',
                    'group' => '*',
                    'key' => $key,
                    'text' => $key,
                    'locale' => $locale,
                ];
            } else {
                // echo This is with namespace ->' . $namespace . $group . $item .'<br />';
                self::$newTexts[md5($namespace. $group. $item. $locale)] = [
                    'namespace' => $namespace,
                    'group' => $group,
                    'key' => $item,
                    'text' => $item,
                    'locale' => $locale,
                ];
            }

            // Here we will get the locale that should be used for the language line. If one
            // was not passed, we will use the default locales which was given to us when
            // the translator was instantiated. Then, we can load the lines and return.
            $locales = $fallback ? $this->localeArray($locale) : [$locale];

            foreach ($locales as $locale) {
                if (! is_null($line = $this->getLine(
                    $namespace, $group, $locale, $item, $replace
                ))) {
                    return $line;
                }
            }
        }

        // If the line doesn't exist, we will return back the key which was requested as
        // that will be quick to spot in the UI if language keys are wrong or missing
        // from the application's language files. Otherwise we can return the line.
        return $this->makeReplacements($line ?: $key, $replace);
    }

    public function getNewTexts()
    {
        return self::$newTexts;
    }
}
