<?php

declare(strict_types=1);

namespace MicroweberPackages\Dusk\Tests;

use Laravel\Dusk\Browser;
use MicroweberPackages\Dusk\DuskCoverage;
use MicroweberPackages\Dusk\DuskPackageServiceProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * Standalone smoke coverage for the Dusk helper package. The browser suite under
 * tests/Browser needs a live Chrome + running server, so here we only assert the
 * package's service provider boots in a bare Testbench app and that its Browser
 * macros / coverage sink are wired.
 */
class StandaloneTest extends StandaloneTestCase
{
    #[Test]
    public function service_provider_boots_and_is_registered(): void
    {
        $this->assertArrayHasKey(
            DuskPackageServiceProvider::class,
            $this->app->getLoadedProviders(),
            'DuskPackageServiceProvider should be loaded by the booted app.'
        );
    }

    #[Test]
    public function browser_macros_are_registered_on_boot(): void
    {
        $this->assertTrue(Browser::hasMacro('switchFrame'));
        $this->assertTrue(Browser::hasMacro('switchFrameDefault'));
        $this->assertTrue(Browser::hasMacro('saveDuskCoverage'));
    }

    #[Test]
    public function dusk_coverage_save_is_a_safe_noop_on_empty_input(): void
    {
        $this->assertTrue(class_exists(DuskCoverage::class));

        // Empty coverage must short-circuit without writing or throwing.
        DuskCoverage::save([]);
        $this->addToAssertionCount(1);
    }
}
