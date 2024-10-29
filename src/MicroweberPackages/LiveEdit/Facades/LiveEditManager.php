<?php

namespace MicroweberPackages\LiveEdit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void addScript(string $id, string $src, array $attributes = [])
 * @method static void removeScript(string $id)
 * @method static void addStyle(string $id, string $src, array $attributes = [])
 * @method static void removeStyle(string $id)
 * @method static void addCustomHeadTag(string $html)
 * @method static string headTags()
 * @method static string styles()
 * @method static string scripts()
 * @method static string customHeadTags()
 * @method static void initMenus()
 * @see \MicroweberPackages\LiveEdit\Repository\LiveEditManagerRepository
 * @mixin \MicroweberPackages\LiveEdit\Repository\LiveEditManagerRepository
 */
class LiveEditManager extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'live_edit_manager';
    }
}
