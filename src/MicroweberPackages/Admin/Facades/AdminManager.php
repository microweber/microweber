<?php

namespace MicroweberPackages\Admin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MicroweberPackages\Admin\AdminManager
 * @mixin \MicroweberPackages\Admin\AdminManager
 */
class AdminManager extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'AdminManager';
    }
}
