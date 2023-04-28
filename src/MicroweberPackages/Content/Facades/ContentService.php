<?php

namespace MicroweberPackages\Content\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MicroweberPackages\Content\Services\ContentService
 * @method static array|false getParents()
 */
class ContentService extends  Facade
{
    protected static function getFacadeAccessor()
    {
        return \MicroweberPackages\Content\Services\ContentService::class;
    }
}
