<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/12/2021
 * Time: 11:54 AM
 */

namespace MicroweberPackages\Translation;

use MicroweberPackages\Import\Formats\XlsxReader;
use MicroweberPackages\Translation\Locale\IntlLocale;
use Symfony\Component\Intl\Exception\MissingResourceException;

class TranslationPackageInstallHelper
{
    static $logger = null;

    public static function getAvailableTranslations($type = 'xlsx')
    {
        $translations = [];

        if ($type == 'json') {
            $langFolder = __DIR__ . '/resources/lang/';
        } else {
            $langFolder = __DIR__ . '/resources/lang_xlsx/';
        }

        foreach (glob($langFolder . '*.'.$type) as $filename) {
            $item = basename($filename);
            $item = no_ext($item);
            $locale_name_sanitized = preg_replace('/^([a-z0-9\s\_\-]+)$/', '', $item);
            if($item != $locale_name_sanitized){
                continue;
            }

            try {
                $translations[$item] = LanguageHelper::getDisplayLanguage($item);
            } catch (MissingResourceException $e) {
                continue;
            }

        }
        if($translations){
           asort($translations);
        }

        if (!$translations) {
            $translations = ['en_US'];
        }

        $en = [];

        if ($translations) {
            foreach ($translations as $translation_key => $translations_lang) {
                if (stristr($translation_key, 'en_')) {
                    $en[$translation_key] = $translations_lang;
                    unset($translations[$translation_key]);
                }
            }
        }
        $translations = array_merge($en, $translations);
        return $translations;
    }

    public static function installLanguage($locale)
    {
        $file = __DIR__ . '/resources/lang_xlsx/' . $locale . '.xlsx';

        if (is_file($file)) {
            if (php_can_use_func('set_time_limit')) {
                @set_time_limit(-0);
            }

            $readFile = new XlsxReader($file);
            $data = $readFile->readData();
            $translations = $data['content'];
            $forImport = [];
            if ($translations) {
                foreach ($translations as $translation_item) {
                    if (!isset($translation_item['translation_locale'])) {
                        $translation_item['translation_locale'] = $locale;
                    }
                    if (isset($translation_item['translation_locale'])) {
                        $forImport[] = $translation_item;

                    }
                }
            }
             $import = new \MicroweberPackages\Translation\TranslationImport();

            if (is_object(self::$logger) and method_exists(self::$logger, 'log')) {
                $import->logger = self::$logger;
            }
            return $import->import($forImport);
        }
    }

}
