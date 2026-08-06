<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Tests\Feature;

use MicroweberPackages\Notification\Tests\TestCase;

/**
 * CMS regression for legacy notification save via notifications_manager.
 */
class LegacyNotificationCmsTest extends TestCase
{
    public function test_legacy_notification_save(): void
    {
        $notification = [];
        $notification['module'] = 'shop';
        $notification['rel_type'] = 'cart_orders';
        $notification['rel_id'] = 1;
        $notification['title'] = 'You have new order';
        $notification['description'] = 'New order is placed';
        $notification['content'] = 'New order in the online shop. Order id: ';

        $result = app('notifications_manager')->save($notification);
        $this->assertIsArray($result);

        // Also via helper
        $result2 = notifications_manager()->save($notification);
        $this->assertIsArray($result2);
    }
}
