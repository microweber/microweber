<?php

namespace MicroweberPackages\LaravelModules\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesCacheRepository;

/**
 * @mixin LaravelModulesCacheRepository
 */
class LaravelModulesCache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LaravelModulesCache';
    }
}
