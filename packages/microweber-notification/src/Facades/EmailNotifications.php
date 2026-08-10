<?php

namespace MicroweberPackages\Notification\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Notification\Services\EmailNotificationsManager;

/**
 * EmailNotifications facade — greppable public API for email notifications manager.
 *
 * @see \MicroweberPackages\Notification\Services\EmailNotificationsManager
 * @mixin \MicroweberPackages\Notification\Services\EmailNotificationsManager
 */
class EmailNotifications extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return EmailNotificationsManager::class;
    }
}
