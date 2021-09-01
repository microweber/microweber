<?php


function multilanguage_is_enabled()
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
