<?php

namespace MicroweberPackages\Security\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Security\XSSSecurity as XSSSecurityService;

/**
 * XSSSecurity facade — greppable public API for XSS security helpers.
 *
 * @see \MicroweberPackages\Security\XSSSecurity
 * @mixin \MicroweberPackages\Security\XSSSecurity
 */
class XSSSecurity extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return XSSSecurityService::class;
    }
}
