<?php

declare(strict_types=1);

namespace MicroweberPackages\Multilanguage\Tests;

use MicroweberPackages\Multilanguage\MultilanguageApi;
use MicroweberPackages\Multilanguage\MultilanguageServiceProvider;
use MicroweberPackages\Multilanguage\TranslateManager;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Standalone smoke test.
 *
 * This package's service provider is tightly coupled to the integrated
 * Microweber CMS (application container, url manager, form builder,
 * repositories), so it cannot boot under a bare Orchestra Testbench app.
 * Instead we assert the package's key classes are present and autoloadable
 * via Composer PSR-4.
 */
class ClassAutoloadTest extends TestCase
{
    #[Test]
    public function key_classes_are_autoloadable(): void
    {
        $this->assertTrue(
            class_exists(MultilanguageServiceProvider::class),
            'MultilanguageServiceProvider should be autoloadable via PSR-4.'
        );
        $this->assertTrue(
            class_exists(MultilanguageApi::class),
            'MultilanguageApi should be autoloadable via PSR-4.'
        );
        $this->assertTrue(
            class_exists(TranslateManager::class),
            'TranslateManager should be autoloadable via PSR-4.'
        );
    }
}
