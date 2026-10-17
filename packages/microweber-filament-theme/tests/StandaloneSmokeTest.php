<?php

declare(strict_types=1);

namespace MicroweberPackages\MicroweberFilamentTheme\Tests;

use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentThemeServiceProvider;

/**
 * Minimal standalone smoke test: proves the package's service provider loads and
 * registers inside a Testbench Laravel app (reaching any assertion means the app
 * booted with the provider + its Filament/Livewire deps present).
 */
class StandaloneSmokeTest extends TestCase
{
    public function test_service_provider_is_loaded(): void
    {
        $this->assertTrue(class_exists(MicroweberFilamentThemeServiceProvider::class));
        $this->assertArrayHasKey(
            MicroweberFilamentThemeServiceProvider::class,
            $this->app->getLoadedProviders()
        );
    }
}
