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

    }
}
