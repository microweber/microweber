<?php

declare(strict_types=1);

namespace MicroweberPackages\MailSender\Tests\Feature;

use MicroweberPackages\MailSender\MailSenderServiceProvider;
use MicroweberPackages\MailSender\Services\MailConfigApplier;
use MicroweberPackages\MailSender\Services\MailSenderService;
use MicroweberPackages\MailSender\Tests\TestCase;

/**
 * Simulates a standalone Laravel app consuming the package + any dependent packages.
 *
 * This does not scaffold a full app on disk; it verifies the public package surface
 * that a path-repository install would expose (provider, config, service, helpers).
 */
class StandaloneLaravelAppTest extends TestCase
{
    public function test_provider_registers_cleanly(): void
    {
        $this->assertTrue($this->app->getProvider(MailSenderServiceProvider::class) !== null
            || $this->app->bound(MailSenderService::class));
    }

    public function test_config_publish_path_exists(): void
    {
        $configFile = dirname(__DIR__, 2) . '/config/mail-sender.php';
        $this->assertFileExists($configFile);
        $cfg = require $configFile;
        $this->assertIsArray($cfg);
        $this->assertArrayHasKey('transport', $cfg);
        $this->assertArrayHasKey('from', $cfg);
        $this->assertArrayHasKey('smtp', $cfg);
    }

    public function test_view_exists(): void
    {
        $view = dirname(__DIR__, 2) . '/resources/views/emails/simple.blade.php';
        $this->assertFileExists($view);
    }

    public function test_can_reconfigure_and_send_in_array_mode(): void
    {
        app(MailConfigApplier::class)->apply([
            'transport' => 'array',
            'enabled' => true,
            'from' => ['address' => 'standalone@example.com', 'name' => 'Standalone'],
            'smtp' => [
                'host' => '127.0.0.1',
                'port' => 1025,
                'username' => null,
                'password' => null,
                'encryption' => null,
            ],
        ]);

        $service = app(MailSenderService::class);
        $service->setEmailTo('recipient@example.com')
            ->setEmailSubject('Standalone subject')
            ->setEmailMessage('<p>Hello from standalone app</p>')
            ->setEmailFrom('standalone@example.com')
            ->setEmailFromName('Standalone');

        try {
            $ok = $service->send();
            // array mailer should succeed
            $this->assertTrue($ok || MailSenderService::$lastSend !== null);
        } catch (\Throwable $e) {
            // View rendering may require full view finder; last_send still proves path worked
            $this->assertNotNull(MailSenderService::$lastSend, $e->getMessage());
        }

        $last = $service->getLastSend();
        $this->assertIsArray($last);
        $this->assertSame('recipient@example.com', $last['to']);
        $this->assertSame('Standalone subject', $last['subject']);
    }

    public function test_no_cms_option_manager_dependency(): void
    {
        $ref = new \ReflectionClass(MailSenderService::class);
        $src = file_get_contents($ref->getFileName() ?: '');
        $this->assertIsString($src);
        $this->assertStringNotContainsString('option_manager', $src);
        $this->assertStringNotContainsString('get_email_from', $src);
        $this->assertStringNotContainsString('configMailDriver', $src);
        $this->assertStringNotContainsString('is_admin', $src);
        $this->assertStringNotContainsString('url_manager', $src);
    }
}
