<?php

namespace MicroweberPackages\ContentField\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\ContentField\ContentFieldManager;

/**
 * ContentField facade — greppable public API for content field manager.
 *
 * @see \MicroweberPackages\ContentField\ContentFieldManager
 * @mixin \MicroweberPackages\ContentField\ContentFieldManager
 */
class ContentField extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ContentFieldManager::class;
    }
}
