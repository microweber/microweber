<?php

namespace MicroweberPackages\Translation;

use MicroweberPackages\Translation\Locale\IntlLocale;
use Symfony\Component\Intl\Exception\MissingResourceException;

class TranslationPackageInstallHelper
{
    static $logger = null;

    public static function getAvailableTranslations($type = 'json')
    {
        $translations = [];

        $langFolder = dirname(__DIR__) . '/resources/lang/';

        foreach (glob($langFolder . '*.' . $type) as $filename) {
            $item = basename($filename);
            $item = pathinfo($item, PATHINFO_FILENAME);
            $locale_name_sanitized = preg_replace('/[^a-z0-9\s\_\-]/i', '', $item);
            if ($item != $locale_name_sanitized) {
                continue;
            }

            try {
                $translations[$item] = LanguageHelper::getDisplayLanguage($item);
            } catch (MissingResourceException $e) {
                continue;
            }
        }

        if ($translations) {
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

    public static function installLanguage(string $locale)
    {
        $locale = preg_replace('/[^a-zA-Z0-9_]/', '', $locale);
        $file = dirname(__DIR__) . '/resources/lang/' . $locale . '.json';

        if (is_file($file)) {
            if (function_exists('php_can_use_func')) {
                if (php_can_use_func('set_time_limit')) {
                    @set_time_limit(0);
                }
            } elseif (function_exists('set_time_limit')) {
                @set_time_limit(0);
            }

            $json = file_get_contents($file);
            $translations = json_decode($json, true);

            $forImport = [];
            if ($translations) {
                foreach ($translations as $translationKey => $translationText) {
                    $forImport[] = [
                        'translation_namespace' => '*',
                        'translation_group' => '*',
                        'translation_key' => $translationKey,
                        'translation_text' => $translationText,
                        'translation_locale' => $locale,
                    ];
                }
            }

            $import = new TranslationImport();

            if (is_object(self::$logger) and method_exists(self::$logger, 'log')) {
                $import->logger = self::$logger;
            }

            return $import->import($forImport);
        }
    }
}