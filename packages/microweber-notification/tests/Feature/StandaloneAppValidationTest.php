<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Tests\Feature;

use MicroweberPackages\Notification\Channels\AppMailChannel;
use MicroweberPackages\Notification\Contracts\NotificationsManagerContract;
use MicroweberPackages\Notification\Facades\Notifications as NotificationsManagerFacade;
use MicroweberPackages\Notification\Models\Notification;
use MicroweberPackages\Notification\Models\NotificationMailLog;
use MicroweberPackages\Notification\Notifications\LegacyNotification;
use MicroweberPackages\Notification\Providers\NotificationServiceProvider;
use MicroweberPackages\Notification\Services\NotificationsManager;
use MicroweberPackages\Notification\Tests\TestCase;

/**
 * Validates the package API surface that a standalone Laravel app would use.
 */
class StandaloneAppValidationTest extends TestCase
{
    public function test_package_classes_are_loadable(): void
    {
        $this->assertTrue(class_exists(NotificationsManager::class));
        $this->assertTrue(interface_exists(NotificationsManagerContract::class));
        $this->assertTrue(class_exists(NotificationServiceProvider::class));
        $this->assertTrue(class_exists(AppMailChannel::class));
        $this->assertTrue(class_exists(LegacyNotification::class));
        $this->assertTrue(class_exists(Notification::class));
        $this->assertTrue(class_exists(NotificationMailLog::class));
    }

    public function test_facade_works(): void
    {
        $count = NotificationsManagerFacade::get_unread_count();
        $this->assertIsInt($count);
    }

    public function test_helpers_work(): void
    {
        $this->assertTrue(function_exists('notifications_manager'));
        $this->assertTrue(function_exists('notification_unread_count'));
        $this->assertInstanceOf(NotificationsManager::class, notifications_manager());
        $this->assertIsInt(notification_unread_count());
    }

    public function test_config_file_exists_and_is_publishable(): void
    {
        $configFile = dirname(__DIR__, 2) . '/config/microweber-notification.php';
        $this->assertFileExists($configFile);
        $cfg = require $configFile;
        $this->assertIsArray($cfg);
        $this->assertArrayHasKey('admin_user_model', $cfg);
        $this->assertArrayHasKey('load_admin_routes', $cfg);
        $this->assertArrayHasKey('admin_route_prefix', $cfg);
    }

    public function test_no_hard_cms_manager_dependency_in_services(): void
    {
        $ref = new \ReflectionClass(NotificationsManager::class);
        $src = file_get_contents($ref->getFileName() ?: '');
        $this->assertIsString($src);
        $this->assertStringNotContainsString('MicroweberPackages\\App\\Managers', $src);
        $this->assertStringNotContainsString('database_manager', $src);
        $this->assertStringNotContainsString('dd(', $src);
    }
}
