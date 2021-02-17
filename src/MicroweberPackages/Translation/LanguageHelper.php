<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/12/2021
 * Time: 2:08 PM
 */

namespace MicroweberPackages\Translation;

use MicroweberPackages\Translation\Locale\IntlLocale;
use Symfony\Component\Intl\Locales;

class LanguageHelper {

    public static function getLanguagesWithDefaultLocale()
    {
        $readyLanguages = [];
        foreach (Locales::getLocales() as $locale) {
            if (mb_strlen($locale)==2) {
                continue;
            }

            $region = IntlLocale::getDisplayRegion($locale);
            $displayLanguage = IntlLocale::getDisplayLanguage($locale);

            if (empty($region) || empty($displayLanguage)) {
                continue;
            }

            // Don't save this language
            if (isset($readyLanguages[$displayLanguage])) {
                continue;
            }

             /****
              * VERRY IMPORTANT!!
              * Set default locale to language if we have a translation for this language
              ****/
            $availableTranslations = TranslationHelper::getAvailableTranslations();
            foreach ($availableTranslations as $languageLocale=>$languageName) {
                if ($displayLanguage == $languageName) {
                    $locale = $languageLocale;
                    $region = IntlLocale::getDisplayRegion($locale);
                    break;
                }
            }

            $readyLanguages[$displayLanguage] = [
                'region'=>$region,
                'display_language'=>$displayLanguage,
                'locale'=>$locale,
                'text'=>$displayLanguage. ' (' .$region.')'
            ];
        }
//dd($readyLanguages);
        return $readyLanguages;

    }

}