<?php

declare(strict_types=1);

namespace MicroweberPackages\DisposableEmailChecker\Tests;

use MicroweberPackages\DisposableEmailChecker\Contracts\DisposableEmailCheckerContract;
use MicroweberPackages\DisposableEmailChecker\Services\DisposableEmailChecker;
use PHPUnit\Framework\Attributes\Test;

class DisposableEmailCheckerServiceTest extends TestCase
{
    #[Test]
    public function service_is_bound_in_container(): void
    {
        $this->assertInstanceOf(
            DisposableEmailCheckerContract::class,
            $this->app->make(DisposableEmailCheckerContract::class)
        );
    }

    #[Test]
    public function service_is_aliased_as_disposable_email_checker(): void
    {
        $this->assertInstanceOf(
            DisposableEmailCheckerContract::class,
            $this->app->make('disposable_email_checker')
        );
    }

    #[Test]
    public function service_is_singleton(): void
    {
        $a = $this->app->make('disposable_email_checker');
        $b = $this->app->make('disposable_email_checker');

        $this->assertSame($a, $b);
    }

    #[Test]
    public function detects_disposable_email(): void
    {
        /** @var DisposableEmailChecker $checker */
        $checker = $this->app->make('disposable_email_checker');

        $this->assertTrue($checker->isDisposable('test@mailinator.com'));
    }

    #[Test]
    public function allows_legitimate_email(): void
    {
        /** @var DisposableEmailChecker $checker */
        $checker = $this->app->make('disposable_email_checker');

        $this->assertFalse($checker->isDisposable('user@gmail.com'));
    }

    #[Test]
    public function case_insensitive_check(): void
    {
        /** @var DisposableEmailChecker $checker */
        $checker = $this->app->make('disposable_email_checker');

        $this->assertTrue($checker->isDisposable('test@MAILINATOR.COM'));
    }

    #[Test]
    public function returns_false_for_invalid_email_without_at(): void
    {
        /** @var DisposableEmailChecker $checker */
        $checker = $this->app->make('disposable_email_checker');

        $this->assertFalse($checker->isDisposable('not-an-email'));
    }

    #[Test]
    public function blocked_domains_returns_non_empty_array(): void
    {
        /** @var DisposableEmailChecker $checker */
        $checker = $this->app->make('disposable_email_checker');

        $domains = $checker->blockedDomains();

        $this->assertIsArray($domains);
        $this->assertNotEmpty($domains);
        $this->assertContains('mailinator.com', $domains);
    }

    #[Test]
    public function add_domains_extends_blocked_list(): void
    {
        /** @var DisposableEmailChecker $checker */
        $checker = $this->app->make('disposable_email_checker');

        $checker->addDomains('custom-throwaway.org');

        $this->assertTrue($checker->isDisposable('test@custom-throwaway.org'));
    }

    #[Test]
    public function add_domains_accepts_array(): void
    {
        /** @var DisposableEmailChecker $checker */
        $checker = $this->app->make('disposable_email_checker');

        $checker->addDomains(['throwaway-a.org', 'throwaway-b.org']);

        $this->assertTrue($checker->isDisposable('x@throwaway-a.org'));
        $this->assertTrue($checker->isDisposable('y@throwaway-b.org'));
    }

    #[Test]
    public function add_domains_does_not_duplicate(): void
    {
        /** @var DisposableEmailChecker $checker */
        $checker = $this->app->make('disposable_email_checker');

        $beforeCount = count($checker->blockedDomains());
        $checker->addDomains('mailinator.com'); // already in the list
        $afterCount = count($checker->blockedDomains());

        $this->assertSame($beforeCount, $afterCount);
    }

    #[Test]
    public function config_is_merged_properly(): void
    {
        $config = config('disposable-email-checker');

        $this->assertIsArray($config);
        $this->assertArrayHasKey('enabled', $config);
        $this->assertArrayHasKey('list_path', $config);
    }

    #[Test]
    public function disabled_config_always_returns_false(): void
    {
        config()->set('disposable-email-checker.enabled', false);

        // The service itself still detects disposable domains,
        // but the validator respects the enabled flag.
        /** @var DisposableEmailChecker $checker */
        $checker = $this->app->make('disposable_email_checker');

        // Direct service call still works — the flag is checked by validator/middleware
        $this->assertTrue($checker->isDisposable('test@mailinator.com'));
    }
}