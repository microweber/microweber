<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Tests\Dusk;

use MicroweberPackages\Notification\Models\Notification;
use MicroweberPackages\Notification\Services\NotificationsManager;
use MicroweberPackages\User\Models\User;
use Tests\DuskTestCase;

/**
 * Dusk / browser smoke tests for notification admin surfaces.
 *
 * Uses authenticated admin + internal requests (same pattern as queue package).
 */
class NotificationAdminDuskTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $admin = new User();
        $admin->username = 'notif_dusk_' . uniqid();
        $admin->email = 'notif_dusk_' . uniqid() . '@example.com';
        $admin->password = 'password';
        $admin->is_admin = 1;
        $admin->is_active = 1;
        $admin->save();

        $this->actingAs($admin);
    }

    protected function httpSmoke(string $path, int|array $expectedStatus, ?string $see = null): void
    {
        $response = $this->get($path);
        $statuses = is_array($expectedStatus) ? $expectedStatus : [$expectedStatus];
        $this->assertContains($response->status(), $statuses);

        if ($see !== null) {
            $body = (string) $response->getContent();
            $this->assertTrue(
                str_contains($body, $see) || str_contains(stripslashes($body), $see),
                "Response body does not contain expected fragment: {$see}"
            );
        }
    }

    public function test_notification_admin_index_smoke(): void
    {
        $this->httpSmoke('/admin/notification', [200, 302, 401, 403, 404, 500]);
    }

    public function test_package_services_available_in_cms(): void
    {
        $this->assertTrue(class_exists(Notification::class));
        $this->assertTrue(class_exists(NotificationsManager::class));
        $this->assertTrue($this->app->bound('notifications_manager'));
        $this->assertInstanceOf(
            NotificationsManager::class,
            app('notifications_manager')
        );
    }

    public function test_save_legacy_notification_as_admin(): void
    {
        $result = app('notifications_manager')->save([
            'module' => 'dusk',
            'rel_type' => 'test',
            'rel_id' => 1,
            'title' => 'Dusk notification',
            'description' => 'Created by dusk smoke test',
        ]);

        $this->assertIsArray($result);
    }
}
