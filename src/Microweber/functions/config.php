<?php

function mw_is_installed()
{
    return (bool)Config::get('microweber.is_installed');
}