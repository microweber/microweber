<?php

declare(strict_types=1);

namespace MicroweberPackages\User\Tests;

use MicroweberPackages\User\ConfirmsPasswords;
use MicroweberPackages\User\Providers\AuthServiceProvider;
use MicroweberPackages\User\Providers\UserServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Standalone smoke test.
 *
 * This package's service providers are tightly coupled to the integrated
 * Microweber CMS (admin events, filament-registry, livewire admin
 * components, user manager/services), so they cannot boot under a bare
 * Orchestra Testbench app. Instead we assert the package's key classes are
 * present and autoloadable via Composer PSR-4.
 */
class ClassAutoloadTest extends TestCase
{
    #[Test]
    public function key_classes_are_autoloadable(): void
    {
        $this->assertTrue(
            class_exists(UserServiceProvider::class),
            'UserServiceProvider should be autoloadable via PSR-4.'
        );
        $this->assertTrue(
            class_exists(AuthServiceProvider::class),
            'AuthServiceProvider should be autoloadable via PSR-4.'
        );
        $this->assertTrue(
            trait_exists(ConfirmsPasswords::class),
            'ConfirmsPasswords trait should be autoloadable via PSR-4.'
        );
    }
}
