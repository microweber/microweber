<?php


namespace MicroweberPackages\Translation\Locale;

use WhiteCube\Lingua\Service as Lingua;
use WhiteCube\Lingua\LanguagesRepository;

class LanguagesData
{
    public static function getLanguagesWithLocales()
    {
        $ready = [];

        $langs = new LanguagesRepository();
        $langs = $langs->languages;

        if ($langs) {
            foreach ($langs as $lang) {
                if (isset($lang["iso-639-1"]) and !empty($lang["iso-639-1"])) {
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
                        if ($locales) {
                            $lang["locales"] = $locales;
                            $lang["locale"] = array_key_first($locales);

                            $ready[] = $lang;
                        }
                    }
                }
            }
        }
        return $ready;

    }


}