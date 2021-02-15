<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/12/2021
 * Time: 11:54 AM
 */

namespace MicroweberPackages\Translation;

use MicroweberPackages\Translation\Locale\IntlLocale;

class TranslationHelper {

    public static function getAvailableTranslations()
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

    public static function installLanguage($locale) {

        $file = __DIR__ .  '/resources/lang_xlsx/'.$locale.'.xlsx';

        if (is_file($file)) {
            $import = new \MicroweberPackages\Translation\TranslationXlsxImport();
            return $import->import($file);
        }
    }

}