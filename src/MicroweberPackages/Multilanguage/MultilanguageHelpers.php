<?php

namespace MicroweberPackages\Multilanguage;


use Illuminate\Support\Facades\DB;
use MicroweberPackages\Option\Repositories\OptionRepository;

class MultilanguageHelpers
{

    public static function multilanguageIsEnabled()
    {

        $isMultilanguageActive = false;

        if (is_module('multilanguage') && get_option('is_active', 'multilanguage_settings') == 'y') {
            $isMultilanguageActive = true;
        }


        if (defined('MW_DISABLE_MULTILANGUAGE')) {
            $isMultilanguageActive = false;
        }

        return $isMultilanguageActive;
    }

}
