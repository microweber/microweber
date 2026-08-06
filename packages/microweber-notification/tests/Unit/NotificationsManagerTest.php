<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Tests\Unit;

use MicroweberPackages\Notification\Contracts\NotificationsManagerContract;
use MicroweberPackages\Notification\Models\Notification;
use MicroweberPackages\Notification\Models\NotificationMailLog;
use MicroweberPackages\Notification\Notifications\LegacyNotification;
use MicroweberPackages\Notification\Services\EmailNotificationsManager;
use MicroweberPackages\Notification\Services\NotificationsManager;
use MicroweberPackages\Notification\Tests\TestCase;

class NotificationsManagerTest extends TestCase
{
    public function test_manager_is_bound_in_container(): void
    {
        $this->assertTrue($this->app->bound(NotificationsManager::class));
        $this->assertTrue($this->app->bound(NotificationsManagerContract::class));
        $this->assertTrue($this->app->bound('notifications_manager'));

        $manager = app(NotificationsManagerContract::class);
        $this->assertInstanceOf(NotificationsManager::class, $manager);
        $this->assertSame($manager, app('notifications_manager'));
    }

    public function test_helper_resolves_manager(): void
    {
        $this->assertTrue(function_exists('notifications_manager'));
        $this->assertInstanceOf(NotificationsManager::class, notifications_manager());
        $this->assertTrue(function_exists('notification_unread_count'));
        $this->assertIsInt(notification_unread_count());
    }

    public function test_save_without_admin_model_is_safe(): void
    {
        config(['microweber-notification.admin_user_model' => 'NonExistent\\UserModel']);

        $result = notifications_manager()->save([
            'module' => 'shop',
            'rel_type' => 'cart_orders',
            'rel_id' => 1,
            'title' => 'Test',
        ]);

        $this->assertIsArray($result);
    }

    public function test_legacy_notification_payload(): void
    {
        $payload = ['title' => 'Hello', 'module' => 'test'];
        $notification = new LegacyNotification($payload);

        $this->assertSame(['database'], $notification->via(new \stdClass()));
        $array = $notification->toArray(new \stdClass());
        $this->assertSame('legacy', $array['notifiable']);
        $this->assertSame($payload, $array['notification']);
    }

    public function test_models_exist_and_table_names(): void
    {
        $this->assertSame('notifications', (new Notification())->getTable());
        $this->assertSame('notifications_mails_log', (new NotificationMailLog())->getTable());
    }

    public function test_email_notifications_manager_constructs(): void
    {
        $manager = new EmailNotificationsManager();
        $this->assertInstanceOf(EmailNotificationsManager::class, $manager);
    }

    public function test_get_unread_count_returns_int(): void
    {
        $count = notifications_manager()->get_unread_count();
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function test_deprecated_methods_return_empty(): void
    {
        $manager = notifications_manager();
        $this->assertSame([], $manager->read(1));
        $this->assertSame([], $manager->reset());
        $this->assertSame([], $manager->delete_selected(['ids' => '1,2']));
    }
}
