<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/12/2021
 * Time: 2:08 PM
 */

namespace MicroweberPackages\Translation;

use MicroweberPackages\Translation\Locale\IntlLocale;
use MicroweberPackages\Translation\Locale\LanguagesData;
use Symfony\Component\Intl\Locales;


class LanguageHelper
{
    public static function getDisplayLanguage($locale_name)
    {

        $locale = IntlLocale::getDisplayName($locale_name);

        if($locale){
            return $locale;
        }

        $locale = \Symfony\Component\Intl\Languages::getName($locale_name);

        if ($locale) {
            return $locale;
        }

        $langData = self::getLangData($locale_name);
        if ($langData and isset($langData['name'])) {

            return $langData['name'];
        }
        return $locale_name;
    }

    public static function getLanguageFlag($locale_name)
    {
        $flag = IntlLocale::getDisplayFlag($locale_name);
        if($flag){
            return $flag;
        }
        $langData = self::getLangData($locale_name);
        if ($langData and isset($langData['flag'])) {
            return $langData['flag'];
        }

    }

    public static function isRTL($locale_name)
    {
        $langData = self::getLangData($locale_name);
        if ($langData and isset($langData['rtl'])) {
            return $langData['rtl'];
        }
        return false;
    }

    public static function getLangData($locale_name)
    {

        $found = false;
        $langs = self::getLanguagesWithDefaultLocale();
        $locale_name_explode = explode("_", $locale_name);

        if ($langs) {
            foreach ($langs as $lang) {
                if ($found) {
                    continue;
                }
                if (isset($lang['name'])) {
                    if (isset($lang['locale']) and strtolower($lang['locale']) == strtolower($locale_name)) {
                        $found = $lang;
                    } else if (isset($lang['locales']) and $lang['locales']) {
                        foreach ($lang['locales'] as $lang_locale_key => $lang_locale_country) {
                            if (strtolower($lang_locale_key) == strtolower($locale_name)) {
                                $found = $lang;
                            }
                        }
                    }
                }
            }

            if (!$found) {
                foreach ($langs as $lang) {
                    if (isset($lang['language']) and strtolower($lang['language']) == strtolower($locale_name_explode[0])) {
                        $found = $lang;
                    }
                }
            }
        }


        return $found;
    }

    public static function getLanguagesWithDefaultLocale()
    {
        $langs = LanguagesData::getLanguagesWithLocales();

        $readyLanguages = [];
        if ($langs) {
            foreach ($langs as $lang) {
              //  $findFlag = IntlLocale::getDisplayFlag($lang['iso-639-1']);
                $flag = IntlLocale::getDisplayFlag($lang['locale']);

                if(!$flag){
                    $flag = $lang['iso-639-1'];
                    $locale_explode = explode('_', $lang['locale']);
                    if (isset($locale_explode[1])) {
                        $flag = strtolower($locale_explode[1]);
                    }

                    if ($flag == 'en') {
                        $flag = 'us';
                    }
                }



                $name = ucfirst($lang['name']);
                $readyLanguages[$name] = [
                    'name' => $name,
                    'language' => $lang['iso-639-1'],
                    'locale' => $lang['locale'],
                    'locales' => $lang['locales'],
                    'rtl' => $lang['rtl'],
                    'flag' => $flag,
                    'text' => $name . ' (' . $lang['native'] . ')'
                ];
            }
        }
        return $readyLanguages;
    }


}
