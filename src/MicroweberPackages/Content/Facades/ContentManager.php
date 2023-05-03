<?php

namespace MicroweberPackages\Content\Facades;

use Illuminate\Support\Facades\Facade;



/**
 * @see \MicroweberPackages\Content\Services\ContentManager
 * @mixin \MicroweberPackages\Content\Services\ContentManager
 *
 */
class ContentManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \MicroweberPackages\Content\Services\ContentManager::class;
    }
}

