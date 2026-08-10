<?php

namespace MicroweberPackages\Http\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Http\HttpService;

/**
 * Http facade — greppable public API for Microweber HTTP client.
 *
 * @see \MicroweberPackages\Http\HttpService
 * @mixin \MicroweberPackages\Http\HttpService
 */
class Http extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return HttpService::class;
    }
}
