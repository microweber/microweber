<?php

namespace MicroweberPackages\Config\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\LaravelConfigExtended\ConfigExtendedRepository;

/**
 * @see ConfigExtendedRepository
 */
class ConfigExtended extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'config';
    }
}