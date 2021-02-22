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
                        $found = $lang['name'];
                    } else if (isset($lang['locales']) and $lang['locales']) {
                        foreach ($lang['locales'] as $lang_locale_key => $lang_locale_country) {
                            if (strtolower($lang_locale_key) == strtolower($locale_name)) {
                                $found = $lang['name'];
                            }
                        }
                    }
                }
            }

            if (!$found) {
                foreach ($langs as $lang) {
                    if (isset($lang['flag']) and strtolower($lang['flag']) == strtolower($locale_name_explode[0])) {
                        $found = $lang['name'];
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
                $flag = $lang['iso-639-1'];

                $locale_explode = explode('_', $lang['locale']);

                if (isset($locale_explode[1])) {
                    $flag = strtolower($locale_explode[1]);
                }

                if ($flag == 'en') {
                    $flag = 'us';
                }

                $name = ucfirst($lang['name']);
                $readyLanguages[$name] = [
                    'name' => $name,
                    'language' => $lang['iso-639-1'],
                    'locale' => $lang['locale'],
                    'locales' => $lang['locales'],
                    'flag' => $flag,
                    'text' => $name . ' (' . $lang['native'] . ')'
                ];
            }
        }
        return $readyLanguages;
    }


    public function getMainLocaleCodes()
    {

        return ["ar_AE",
            "bg_BG",
            "ca_ES",
            "cs_CZ",
            "da_DK",
            "de_DE",
            "el_GR",
            "en_US",
            "es_ES",
            "fi_FI",
            "fr_FR",
            "he_IL",
            "hu_HU",
            "id_ID",
            "it_IT",
            "ja_JP",
            "ko_KR",
            "ms_MY",
            "nb_NO",
            "nl_NL",
            "pl_PL",
            "pt_BR",
            "pt_PT",
            "ro_RO",
            "ru_RU",
            "sv_SE",
            "th_TH",
            "tl_PH",
            "tr_TR",
            "uk_UA",
            "vi_VN",
            "zh_CN",
            "zh_TW"];
    }


}