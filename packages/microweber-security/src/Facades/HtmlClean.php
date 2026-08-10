<?php

namespace MicroweberPackages\Security\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Security\HtmlClean as HtmlCleanService;

/**
 * HtmlClean facade — greppable public API for HTML cleaning.
 *
 * @see \MicroweberPackages\Security\HtmlClean
 * @mixin \MicroweberPackages\Security\HtmlClean
 */
class HtmlClean extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return HtmlCleanService::class;
    }
}
