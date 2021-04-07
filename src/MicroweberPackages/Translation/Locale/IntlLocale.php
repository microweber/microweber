<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/12/2021
 * Time: 11:24 AM
 */

namespace MicroweberPackages\Translation\Locale;

use MicroweberPackages\Translation\LanguageHelper;
use MicroweberPackages\Translation\Locale\Traits\DetailsByLocaleTrait;
use MicroweberPackages\Translation\Locale\Traits\LanguagesByLocaleTrait;
use MicroweberPackages\Translation\Locale\Traits\RegionByLocaleTrait;

/**
 * @deprecated please use LanguageHelper class
 */
class IntlLocale
{
    use DetailsByLocaleTrait, LanguagesByLocaleTrait, RegionByLocaleTrait;

    /**
     * @deprecated please use LanguageHelper class
     */
    public static function getDisplayRegion($locale)
    {
        if (isset(self::$regionsByLocale[$locale])) {
            return self::$regionsByLocale[$locale];
        }

        return false;
    }

    /**
     * @deprecated please use LanguageHelper class
     */
    public static function getDisplayLanguage($locale)
    {

       return  LanguageHelper::getDisplayLanguage($locale);


    }

    /**
     * @deprecated please use LanguageHelper class
     */
    public static function getDisplayFlag($locale)
    {
        if (isset(self::$detailsByLocale[$locale])) {
            $details = self::$detailsByLocale[$locale];
            if (isset($details['flag']) and $details['flag']) {
                return $details['flag'];
            }
            $localee = explode('_', $locale);

            if (isset($localee[1])) {
                return strtolower($localee[1]);
            }
        }
        return $locale;
    }
}