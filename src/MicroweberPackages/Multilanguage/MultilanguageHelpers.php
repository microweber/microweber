<?php

namespace MicroweberPackages\Multilanguage;


use Illuminate\Support\Facades\DB;
use MicroweberPackages\Option\Repositories\OptionRepository;

class MultilanguageHelpers
{

    public static $isEnabled = false;

    public static function multilanguageIsEnabled()
    {
        return true;
        return self::$isEnabled;
    }

    public static function setMultilanguageEnabled($enabled)
    {
        return self::$isEnabled = $enabled;
    }

}
