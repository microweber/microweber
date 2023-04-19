<?php

namespace MicroweberPackages\LiveEdit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MicroweberPackages\LiveEdit\Providers\LiveEditManager
 * @mixin \MicroweberPackages\LiveEdit\Providers\LiveEditManager
 */
class LiveEditManager extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'LiveEditManager';
    }
}
