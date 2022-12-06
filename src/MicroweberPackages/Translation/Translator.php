<?php

namespace MicroweberPackages\Translation;


class Translator extends \Illuminate\Translation\Translator
{
    public static $newKeys = [];

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
//        $pairs = array(
//            "\x03" => "",
//            "\x05" => "",
//            "\x0E" => "",
//            "\x16" => "",
//        );
//        $key = strtr($key, $pairs);

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
            if ($namespace and is_string($namespace) and $namespace != '*') {
                $this->load($namespace, $group, $locale);
                // load namespace translations
                $line2 = $this->loaded[$namespace][$group][$locale][$key] ?? null;
                if ($line2) {
                     $item = $line2;
                }
            }


          //

            if (empty($item)) {
//            echo 'This is without namespace, only key ->'.$key . '<br />';
//            exit;
               // self::$newKeys[md5($key . '**')] = [
                self::$newKeys[md5('**' . $key)] = [
                    'translation_namespace' => '*',
                    'translation_group' => '*',
                    'translation_key' => $key
                ];
            }

            // Here we will get the locale that should be used for the language line. If one
            // was not passed, we will use the default locales which was given to us when
            // the translator was instantiated. Then, we can load the lines and return.
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


         // exit( 'This is with namespace ->' . $namespace . $group . $item );

                self::$newKeys[md5($namespace . $group . $item)] = [
                    'translation_namespace' => $namespace,
                    'translation_group' => $group,
                    'translation_key' => $item
                ];
            }
        }

        // If the line doesn't exist, we will return back the key which was requested as
        // that will be quick to spot in the UI if language keys are wrong or missing
        // from the application's language files. Otherwise we can return the line.
        return $this->makeReplacements($line ?: $key, $replace);
    }

    public function getNewKeys()
    {
        return self::$newKeys;
    }
}
