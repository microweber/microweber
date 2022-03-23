<?php


namespace MicroweberPackages\Translation\Locale;

use WhiteCube\Lingua\Service as Lingua;
use WhiteCube\Lingua\LanguagesRepository;

class LanguagesData
{
    public static $cache = [];

    public static function getLanguagesWithLocales()
    {
        if(self::$cache){
            return self::$cache;
        }

        $ready = [];

        $langs = new LanguagesRepository();

        $langs = $langs->languages;
        $main_locales = self::getMainLocaleCodes();
        $rtl_langs = self::getRtlLangs();
        $default_locales = self::getLangDefaultLocale();
        if ($langs) {


            foreach ($langs as $lang) {
                if (isset($lang["iso-639-1"]) and !empty($lang["iso-639-1"])) {

                    $isRtl = false;
                    if (in_array(strtolower($lang["iso-639-1"]), $rtl_langs)) {
                        $isRtl = true;
                    }
                    if (isset($lang["countries"]) and !empty($lang["countries"])) {
                        $locales = [];
                        foreach ($lang["countries"] as $loc => $country) {
                            if ($lang['iso-639-1'] . '_' . $loc == $lang['iso-639-1'] . '_' . strtoupper($lang['iso-639-1'])) {
                                $locales[$lang['iso-639-1'] . '_' . $loc] = $country;
                                unset($lang["countries"][$loc]);
                            }
                        }
                        if ($lang["countries"]) {
                            foreach ($lang["countries"] as $loc => $country) {
                                $locales[$lang['iso-639-1'] . '_' . $loc] = $country;
                            }
                        }


                        if ($locales and !empty($locales)) {
                            uksort($locales, function ($a, $b) use ($main_locales) {
                                if ($b and in_array($b, $main_locales)) {
                                    return 1;
                                } else {
                                    return 0;
                                }

                            });
                        }

                        if ($locales) {
                            $lang["locales"] = $locales;

                            $default_locale = array_key_exists($lang["name"], $default_locales) ? $default_locales[$lang["name"]] : null;
                            if ( $default_locale and !empty($default_locale)) {
                                $lang["locale"] = $default_locale;
                            } else {
                                $lang["locale"] = array_key_first($locales);
                            }

                            $lang["rtl"] = $isRtl;

                            $ready[] = $lang;
                        }
                    }
                }
            }
        }
        self::$cache = $ready;
        return $ready;

    }

    public static function getMainLocaleCodes()
    {

        return [
            "ar_SA",
            "af_ZA",
            "bg_BG",
            "bs_EU",
            "ca_ES",
            "cs_CZ",
            "da_DK",
            "de_DE",
            "el_GR",
            "en_US",
            "en_GB",
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

    public static function getRtlLangs()
    {

        $rtl_langs = array('ar', 'arc', 'dv', 'far', 'khw', 'ks', 'ps', 'ur', 'yi');
        return $rtl_langs;
    }

    public static function getLangDefaultLocale() {
        return [
            "chinese" => "zh_CN"
        ];
    }
}
