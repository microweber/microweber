<?php

declare(strict_types=1);

namespace MicroweberPackages\Module\Tests;

use MicroweberPackages\Module\ModuleManager;
use MicroweberPackages\Module\ModuleServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Standalone smoke test.
 *
 * This package's service provider is tightly coupled to the integrated
 * Microweber CMS (admin, filament-registry, laravel-modules, marketplace),
 * so it cannot boot under a bare Orchestra Testbench app. Instead we assert
 * the package's key classes are present and autoloadable via Composer PSR-4.
 */
class ClassAutoloadTest extends TestCase
{
    #[Test]
    public function key_classes_are_autoloadable(): void
    {
        $this->assertTrue(
            class_exists(ModuleServiceProvider::class),
            'ModuleServiceProvider should be autoloadable via PSR-4.'
        );
        $this->assertTrue(
            class_exists(ModuleManager::class),
            'ModuleManager should be autoloadable via PSR-4.'
        );
    }
}
