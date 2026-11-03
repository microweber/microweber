<?php

declare(strict_types=1);

namespace MicroweberPackages\Option\Tests;

use MicroweberPackages\Option\GlobalOptions;
use MicroweberPackages\Option\OptionManager;
use MicroweberPackages\Option\Providers\OptionServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Standalone smoke test.
 *
 * This package's service provider is tightly coupled to the integrated
 * Microweber CMS (option models/repositories, multilanguage translate
 * manager, content-field module), so it cannot boot under a bare Orchestra
 * Testbench app. Instead we assert the package's key classes are present and
 * autoloadable via Composer PSR-4.
 */
class ClassAutoloadTest extends TestCase
{
    #[Test]
    public function key_classes_are_autoloadable(): void
    {
        $this->assertTrue(
            class_exists(OptionServiceProvider::class),
            'OptionServiceProvider should be autoloadable via PSR-4.'
        );
        $this->assertTrue(
            class_exists(OptionManager::class),
            'OptionManager should be autoloadable via PSR-4.'
        );
        $this->assertTrue(
            class_exists(GlobalOptions::class),
            'GlobalOptions should be autoloadable via PSR-4.'
        );
    }
}
