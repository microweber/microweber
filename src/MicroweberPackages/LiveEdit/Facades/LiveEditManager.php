<?php

namespace MicroweberPackages\LiveEdit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MicroweberPackages\LiveEdit\LiveEditManager
 * @mixin \MicroweberPackages\LiveEdit\LiveEditManager
 */
class LiveEditManager extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'LiveEditManager';
    }
}
