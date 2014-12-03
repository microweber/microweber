<?php

function mw_is_installed()
{
    static $is = false;

    if ($is == false) {
        $is = Config::get('microweber.is_installed');
    }
    return (bool)$is;
}