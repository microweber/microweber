<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Notification\Services\NotificationsManager;

/**
 * Notifications facade — greppable public API for notifications manager.
 *
 * @method static array<string, mixed> save(array<string, mixed>|string $params)
 * @method static array<int, mixed>|int get(array<string, mixed>|string|false $params = false)
 * @method static int get_unread_count()
 *
 * @see \MicroweberPackages\Notification\Services\NotificationsManager
 * @mixin \MicroweberPackages\Notification\Services\NotificationsManager
 */
class Notifications extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return NotificationsManager::class;
    }
}
