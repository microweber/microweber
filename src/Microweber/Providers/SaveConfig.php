<?php
namespace Microweber\Providers;

use Illuminate\Config\Repository as BaseConfig;
//use \File;

class SaveConfig extends BaseConfig
{
    public function save($path = '')
    {
        dd(__FILE__, $path);
    }
}