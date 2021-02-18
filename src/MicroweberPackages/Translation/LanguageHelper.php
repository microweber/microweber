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


    public static function getLanguagesWithDefaultLocale()
    {
        $langs = LanguagesData::getLanguagesWithLocales();

        $readyLanguages = [];
        if ($langs) {
            foreach ($langs as $lang) {
                $flag = $lang['iso-639-1'];

                $locale_explode = explode('_', $lang['locale']);

                if (isset($locale_explode[1])) {
                    $flag =  strtolower($locale_explode[1]);
                }

                $name = ucfirst($lang['name']);
                $readyLanguages[$name] = [
                    'name' => $name,
                    'language' => $lang['iso-639-1'],
                    'locale' => $lang['locale'],
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


//    public static function __OLD__getLanguagesWithDefaultLocale333()
//    {
//        $readyLanguages = [];
//        foreach (Locales::getLocales() as $locale) {
//            if (mb_strlen($locale) == 2) {
//                continue;
//            }
//
//            $region = IntlLocale::getDisplayRegion($locale);
//            $displayLanguage = IntlLocale::getDisplayLanguage($locale);
//
//            if (empty($region) || empty($displayLanguage)) {
//                continue;
//            }
//
//            // Don't save this language
//            if (isset($readyLanguages[$displayLanguage])) {
//                continue;
//            }
//
//            /****
//             * VERRY IMPORTANT!!
//             * Set default locale to language if we have a translation for this language
//             ****/
//            $availableTranslations = TranslationHelper::getAvailableTranslations();
//            foreach ($availableTranslations as $languageLocale => $languageName) {
//                if ($displayLanguage == $languageName) {
//                    $locale = $languageLocale;
//                    $region = IntlLocale::getDisplayRegion($locale);
//                    break;
//                }
//            }
//
//            $readyLanguages[$displayLanguage] = [
//                'region' => $region,
//                'display_language' => $displayLanguage,
//                'locale' => $locale,
//                'text' => $displayLanguage . ' (' . $region . ')'
//            ];
//        }
////dd($readyLanguages);
//        return $readyLanguages;
//
//    }

}