<?php

declare(strict_types=1);

namespace MicroweberPackages\MailSender\Tests\Feature;

use MicroweberPackages\MailSender\Contracts\MailSenderContract;
use MicroweberPackages\MailSender\Services\MailConfigApplier;
use MicroweberPackages\MailSender\Services\MailSenderService;
use MicroweberPackages\MailSender\Tests\TestCase;

/**
 * Integration: container bindings, config, and helpers all work together.
 */
class StandaloneIntegrationTest extends TestCase
{
    public function test_bindings(): void
    {
        $a = app(MailSenderService::class);
        $b = app(MailSenderService::class);
        // Transient binding — new instance each resolve
        $this->assertInstanceOf(MailSenderService::class, $a);
        $this->assertInstanceOf(MailSenderService::class, $b);
        $this->assertNotSame($a, $b);

        $contract = app(MailSenderContract::class);
        $this->assertInstanceOf(MailSenderService::class, $contract);

        $alias = app('mail-sender');
        $this->assertInstanceOf(MailSenderService::class, $alias);
    }

    public function test_config_is_merged(): void
    {
        $this->assertNotNull(config('mail-sender'));
        $this->assertArrayHasKey('transport', config('mail-sender'));
        $this->assertArrayHasKey('from', config('mail-sender'));
        $this->assertArrayHasKey('smtp', config('mail-sender'));
    }

    public function test_disable_via_config(): void
    {
        config(['mail-sender.enabled' => false]);
        $service = app(MailSenderService::class);
        $this->assertFalse($service->send('user@example.com', 'S', 'M'));
    }

    public function test_reapply_config_updates_laravel_mail(): void
    {
        /** @var MailConfigApplier $applier */
        $applier = app(MailConfigApplier::class);
        $applier->apply([
            'transport' => 'log',
            'from' => ['address' => 'reapply@example.com', 'name' => 'Reapply'],
            'smtp' => [],
        ]);

        $this->assertSame('reapply@example.com', config('mail.from.address'));
        $this->assertSame('log', config('mail.default'));
    }
}
