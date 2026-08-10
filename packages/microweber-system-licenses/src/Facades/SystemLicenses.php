<?php

namespace MicroweberPackages\SystemLicenses\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\SystemLicenses\SystemLicensesManager;

/**
 * SystemLicenses facade — greppable public API for system licenses manager.
 *
 * @see \MicroweberPackages\SystemLicenses\SystemLicensesManager
 * @mixin \MicroweberPackages\SystemLicenses\SystemLicensesManager
 */
class SystemLicenses extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SystemLicensesManager::class;
    }
}
