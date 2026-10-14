<?php

declare(strict_types=1);

namespace MicroweberPackages\Multisite\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Multisite\MultisiteManager;

/**
 * Multisite facade — greppable public API for site resolution + isolation.
 *
 * @method static string|null current()
 * @method static bool isActive()
 * @method static array sites()
 * @method static string userfilesFolder(?string $site = null)
 * @method static string cachePath(?string $site = null)
 * @method static string sessionPath(?string $site = null)
 * @method static string cachePrefix(?string $site = null)
 * @method static string sessionCookie(?string $site = null)
 * @method static void applyIsolation(?string $site = null)
 * @method static \MicroweberPackages\Multisite\MultisiteManager setSite(?string $site)
 *
 * @see \MicroweberPackages\Multisite\MultisiteManager
 * @mixin \MicroweberPackages\Multisite\MultisiteManager
 */
class Multisite extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MultisiteManager::class;
    }
}
