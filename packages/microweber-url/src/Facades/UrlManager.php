<?php

namespace MicroweberPackages\Url\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Url\UrlManagerService;

/**
 * UrlManager facade — greppable public API for the URL manager.
 *
 * @see \MicroweberPackages\Url\UrlManagerService
 * @mixin \MicroweberPackages\Url\UrlManagerService
 */
class UrlManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return UrlManagerService::class;
    }
}
