<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/12/2021
 * Time: 11:54 AM
 */

namespace MicroweberPackages\Translation;


class TranslationHelper {

    public function getAvailableTranslations()
    {
        $translations = [];

        $langFolder = __DIR__ .  '/resources/lang_json/';

        foreach (glob($langFolder . '*.json') as $filename) {
            $item = basename($filename);
            $item = no_ext($item);
            $translations[$item] = IntlLocale::getDisplayLanguage($item);
        }

        return $translations;
    }

}