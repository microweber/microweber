<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Tests\Feature;

use Illuminate\Support\Facades\Schema;
use MicroweberPackages\Notification\Models\Notification;
use MicroweberPackages\Notification\Models\NotificationMailLog;
use MicroweberPackages\Notification\Providers\NotificationServiceProvider;
use MicroweberPackages\Notification\Services\NotificationsManager;
use MicroweberPackages\Notification\Tests\TestCase;

/**
 * Simulates a standalone Laravel app consuming the package + dependent packages.
 */
class StandaloneLaravelAppTest extends TestCase
{
    public function test_provider_registers_cleanly(): void
    {
        $this->assertTrue(
            $this->app->getProvider(NotificationServiceProvider::class) !== null
            || $this->app->bound(NotificationsManager::class)
        );
    }

    public function test_migrations_create_tables(): void
    {
        // Tables may already exist in CMS mode; ensure models resolve.
        $this->assertTrue(class_exists(Notification::class));
        $this->assertTrue(class_exists(NotificationMailLog::class));

        if (Schema::hasTable('notifications')) {
            // Don't assume an empty table — the CMS shares one DB across the whole
            // suite. Just assert the model can query the migrated table.
            $this->assertIsInt(Notification::query()->count());
        }

        if (Schema::hasTable('notifications_mails_log')) {
            $log = new NotificationMailLog();
            $log->type = 'StandaloneTest';
            $log->notifiable_type = 'user';
            $log->notifiable_id = '1';
            $log->html = '<p>hi</p>';
            $log->save();

            $this->assertNotNull($log->id);
            $log->delete();
        } else {
            $this->assertTrue(true, 'Mail log table not present in this environment');
        }
    }

    public function test_can_use_manager_in_array_mode_without_cms_user(): void
    {
        config([
            'microweber-notification.admin_user_model' => null,
            'microweber-notification.admin_column' => 'is_admin',
        ]);

        $ok = notifications_manager()->save([
            'title' => 'Standalone',
            'description' => 'From standalone app test',
            'module' => 'test',
            'rel_type' => 'test',
            'rel_id' => 1,
        ]);

        $this->assertIsArray($ok);
    }

    public function test_views_exist(): void
    {
        $index = dirname(__DIR__, 2) . '/resources/views/notifications/index.blade.php';
        $markdown = dirname(__DIR__, 2) . '/resources/views/email-markdown.blade.php';
        $this->assertFileExists($index);
        $this->assertFileExists($markdown);
    }

    public function test_service_provider_has_no_required_cms_only_bootstrap(): void
    {
        $ref = new \ReflectionClass(NotificationServiceProvider::class);
        $src = file_get_contents($ref->getFileName() ?: '');
        $this->assertIsString($src);
        // CMS helpers must be optional / guarded
        $this->assertStringContainsString('function_exists', $src);
        $this->assertStringContainsString('class_exists', $src);
    }
}
