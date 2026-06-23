<?php

namespace MicroweberPackages\Config\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Config\ConfigRepository;

/**
 * @see ConfigRepository
 */
class ConfigExtended extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'config';
    }
}