<?php

namespace MicroweberPackages\Microweber\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @see \MicroweberPackages\Microweber\Repositories\MicroweberRepository
 *
 * @method static array getModules()
 * @method static void module(string $moduleClass)
 * @method static bool hasModule(string $type)
 */
class Microweber extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'microweber';
    }
}
