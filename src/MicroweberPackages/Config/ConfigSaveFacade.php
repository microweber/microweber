<?php

namespace MicroweberPackages\Config;

use Illuminate\Support\Facades\Facade;

class ConfigSaveFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Config';
    }
}