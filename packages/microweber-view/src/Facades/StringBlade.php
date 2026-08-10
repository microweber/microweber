<?php

namespace MicroweberPackages\View\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\View\StringBlade as StringBladeService;

/**
 * StringBlade facade — greppable public API for string blade rendering.
 *
 * @see \MicroweberPackages\View\StringBlade
 * @mixin \MicroweberPackages\View\StringBlade
 */
class StringBlade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return StringBladeService::class;
    }
}
