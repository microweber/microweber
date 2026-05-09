<?php

namespace MicroweberPackages\Translation;


class Translator extends \Illuminate\Translation\Translator
{
    /*
     * cycle-N (post-cycle-116 OOM hunt): converted from `public static`
     * to `public` instance property. The static accumulator never got
     * reset between phpunit tests, so every untranslated key
     * encountered during a render added an entry that survived for the
     * lifetime of the PHP process — directly contributing to the
     * ~6MB-per-test leak documented in project memory
     * `project_test_architecture`. As an instance property it dies
     * with the Translator (which Laravel rebinds on every app rebuild
     * between tests). No external callers read it via the static-
     * `Translator::$...` syntax (verified with grep) — only the
     * internal `clearNewKeys()` / `getNewKeys()` access, both
     * updated below.
     */
    public $newKeys = [];

    public function clearNewKeys()
    {
        $this->newKeys = [];
    }

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
                $this->newKeys[md5('**' . $key)] = [
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

                $this->newKeys[md5($namespace . $group . $item)] = [
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
        return $this->newKeys;
    }
}
