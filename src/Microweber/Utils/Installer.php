<?php


namespace Microweber\Utils;

use Microweber\Module;

class Installer
{
    function __construct()
    {

    }

    public function run()
    {

        mw()->module->install();

    }

}