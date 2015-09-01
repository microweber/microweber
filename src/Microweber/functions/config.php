<?php

function mw_is_installed()
{
    static $is = null;

    if ($is === null) {
        $is = Config::get('microweber.is_installed');
    }
    return (bool)$is;
}