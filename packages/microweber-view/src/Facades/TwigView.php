<?php

namespace MicroweberPackages\View\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\View\TwigView as TwigViewService;

/**
 * TwigView facade — greppable public API for Twig view rendering.
 *
 * @see \MicroweberPackages\View\TwigView
 * @mixin \MicroweberPackages\View\TwigView
 */
class TwigView extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TwigViewService::class;
    }
}
