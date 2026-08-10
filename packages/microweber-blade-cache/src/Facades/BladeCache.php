<?php

namespace MicroweberPackages\BladeCache\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\BladeCache\BladeCacheService;

/**
 * BladeCache facade — greppable public API for blade view cache.
 *
 * @see \MicroweberPackages\BladeCache\BladeCacheService
 * @mixin \MicroweberPackages\BladeCache\BladeCacheService
 */
class BladeCache extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BladeCacheService::class;
    }
}
