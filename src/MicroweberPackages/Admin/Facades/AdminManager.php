<?php

namespace MicroweberPackages\Admin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MicroweberPackages\Admin\Services\AdminManager
 * @mixin \MicroweberPackages\Admin\Services\AdminManager
 */
class AdminManager extends Facade
{
    public static function getFacadeAccessor()
    {
        return \MicroweberPackages\Admin\Services\AdminManager::class;
    }
}
