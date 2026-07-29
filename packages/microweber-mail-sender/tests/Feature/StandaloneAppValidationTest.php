<?php

declare(strict_types=1);

namespace MicroweberPackages\MailSender\Tests\Feature;

use MicroweberPackages\MailSender\Services\MailConfigApplier;
use MicroweberPackages\MailSender\Services\MailSenderService;
use MicroweberPackages\MailSender\Tests\TestCase;

/**
 * Validates the package API surface that a standalone Laravel app would use.
 */
class StandaloneAppValidationTest extends TestCase
{
    public function test_package_classes_are_loadable(): void
    {
        $this->assertTrue(class_exists(MailSenderService::class));
        $this->assertTrue(class_exists(MailConfigApplier::class));
        $this->assertTrue(class_exists(\MicroweberPackages\MailSender\MailSenderServiceProvider::class));
    }

    public function test_service_usable_without_cms_helpers(): void
    {
        // Old entangled path must not be required
        $this->assertFalse(
            class_exists(\MicroweberPackages\Utils\Mail\MailSender::class, false)
        );

        $service = app(MailSenderService::class);
        $this->assertInstanceOf(MailSenderService::class, $service);
        $this->assertIsArray($service->getStatistics());
        $this->assertIsArray($service->selfTest());
    }

    public function test_full_api_surface_for_external_apps(): void
    {
        $service = app(MailSenderService::class);

        $this->assertIsBool($service->send('bad', 's', 'm'));
        $this->assertIsArray($service->getStatistics());
        $this->assertIsArray($service->selfTest());
        $this->assertTrue(method_exists($service, 'execSend'));
        $this->assertTrue(method_exists($service, 'test'));
    }

    public function test_facade_works(): void
    {
        if (!class_exists(\MicroweberPackages\MailSender\Facades\MailSender::class)) {
            $this->markTestSkipped('Facade not loaded');
        }

        $stats = \MicroweberPackages\MailSender\Facades\MailSender::getStatistics();
        $this->assertIsArray($stats);
    }

    public function test_helpers_work(): void
    {
        $this->assertTrue(function_exists('mail_sender'));
        $this->assertTrue(function_exists('mail_sender_stats'));
        $this->assertInstanceOf(MailSenderService::class, mail_sender());
        $this->assertIsArray(mail_sender_stats());
    }

    public function test_config_applier_is_bound(): void
    {
        $this->assertTrue($this->app->bound(MailConfigApplier::class));
        $a = app(MailConfigApplier::class);
        $b = app(MailConfigApplier::class);
        $this->assertSame($a, $b);
    }
}
