<?php



function mw_is_installed(){

    $installed = Config::get('microweber.is_installed');

    if (!$installed) {
        return false;
    }
    return true;
}

