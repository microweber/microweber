<?php

namespace Coolsam\Modules\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Coolsam\Modules\Modules
 */
class FilamentModules extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Coolsam\Modules\Modules::class;
    }
}
