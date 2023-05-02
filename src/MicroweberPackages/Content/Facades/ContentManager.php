<?php

namespace MicroweberPackages\Content\Facades;

use Illuminate\Support\Facades\Facade;

class ContentManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \MicroweberPackages\Content\Services\ContentManager::class;
    }
}

