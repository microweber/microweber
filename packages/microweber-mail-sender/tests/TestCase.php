<?php

declare(strict_types=1);

namespace MicroweberPackages\MailSender\Tests;

/**
 * Base test case for the mail-sender package.
 *
 * Uses the full CMS application when available (Microweber monorepo),
 * otherwise Orchestra Testbench for standalone package testing.
 */
if (class_exists(\Orchestra\Testbench\TestCase::class) && !trait_exists(\Tests\CreatesApplication::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        protected function getPackageProviders($app): array
        {
            return [
                \MicroweberPackages\MailSender\MailSenderServiceProvider::class,
            ];
        }

        protected function getEnvironmentSetUp($app): void
        {
            $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
            $app['config']->set('app.url', 'http://localhost');
            $app['config']->set('database.default', 'testing');
            $app['config']->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);
            $app['config']->set('mail.default', 'array');
            $app['config']->set('mail-sender.transport', 'array');
            $app['config']->set('mail-sender.from.address', 'noreply@example.com');
            $app['config']->set('mail-sender.from.name', 'Test Sender');
        }
    }
} else {
    abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
    {
        use \Tests\CreatesApplication;

        protected function setUp(): void
        {
            parent::setUp();

            if (config('mail-sender') === null) {
                config(['mail-sender' => require __DIR__ . '/../config/mail-sender.php']);
            }

            config([
                'mail.default' => 'array',
                'mail-sender.transport' => 'array',
                'mail-sender.from.address' => config('mail-sender.from.address') ?: 'noreply@example.com',
                'mail-sender.from.name' => config('mail-sender.from.name') ?: 'Test Sender',
            ]);

            if (!$this->app->bound(\MicroweberPackages\MailSender\Services\MailSenderService::class)) {
                $this->app->register(\MicroweberPackages\MailSender\MailSenderServiceProvider::class);
            }

            // Re-apply after our test config overrides.
            if ($this->app->bound(\MicroweberPackages\MailSender\Services\MailConfigApplier::class)) {
                $this->app->make(\MicroweberPackages\MailSender\Services\MailConfigApplier::class)->apply();
            }
        }
    }
}
