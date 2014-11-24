<?php


namespace Weber\Utils;

use Weber\Module;
use Cache;

class Installer
{
    function __construct()
    {

    }

    public function run()
    {
        Cache::flush();

        wb()->modules->install();

    }

}