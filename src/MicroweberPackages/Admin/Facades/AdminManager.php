<?php

namespace MicroweberPackages\Admin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \MicroweberPackages\Admin\AdminManager
 */
class AdminManager extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'AdminManager';
    }
}
