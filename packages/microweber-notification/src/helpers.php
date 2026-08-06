<?php

declare(strict_types=1);

use MicroweberPackages\Notification\Contracts\NotificationsManagerContract;
use MicroweberPackages\Notification\Services\NotificationsManager;

if (! function_exists('notifications_manager')) {
    /**
     * Resolve the notifications manager service.
     */
    function notifications_manager(): NotificationsManager
    {
        /** @var NotificationsManager $manager */
        $manager = app(NotificationsManagerContract::class);

        return $manager;
    }
}

if (! function_exists('notification_unread_count')) {
    /**
     * Count of stored notifications (legacy unread counter).
     */
    function notification_unread_count(): int
    {
        return notifications_manager()->get_unread_count();
    }
}
