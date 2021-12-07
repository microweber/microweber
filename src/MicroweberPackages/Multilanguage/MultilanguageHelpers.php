<?php

namespace MicroweberPackages\Multilanguage;


use Illuminate\Support\Facades\DB;
use MicroweberPackages\Option\Repositories\OptionRepository;

class MultilanguageHelpers
{

    public static $isEnabled = false;

    public static function multilanguageIsEnabled()
    {

        if (!self::$isEnabled) {
            return false;
        }

        if (defined('MW_DISABLE_MULTILANGUAGE') and MW_DISABLE_MULTILANGUAGE == true) {
            return false;
        }

        return self::$isEnabled;
    }

    public static function setMultilanguageEnabled($enabled)
    {
        return self::$isEnabled = $enabled;
    }

}
