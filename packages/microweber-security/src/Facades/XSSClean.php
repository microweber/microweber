<?php

namespace MicroweberPackages\Security\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Security\XSSClean as XSSCleanService;

/**
 * XSSClean facade — greppable public API for XSS cleaning.
 *
 * @see \MicroweberPackages\Security\XSSClean
 * @mixin \MicroweberPackages\Security\XSSClean
 */
class XSSClean extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return XSSCleanService::class;
    }
}
