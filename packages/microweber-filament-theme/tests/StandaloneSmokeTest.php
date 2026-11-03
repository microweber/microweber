<?php

declare(strict_types=1);

namespace MicroweberPackages\MicroweberFilamentTheme\Tests;

use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentThemeServiceProvider;
use MicroweberPackages\MicroweberFilamentTheme\Palette;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Standalone smoke test for the Filament theme package.
 *
 * This package is primarily a built (Webpack) CSS/JS asset bundle; its PHP side
 * is a Filament plugin + service provider that only come alive inside a fully
 * booted Filament admin panel (Filament/Livewire/Blade-icons providers), which
 * is a monorepo-only runtime. So here we assert — without booting the app — that
 * the dependency-light PHP surface (service provider class, colour palette) is
 * present and well formed, giving the package a real green standalone suite.
 */
class StandaloneSmokeTest extends PHPUnitTestCase
{
    public function test_service_provider_class_is_autoloadable(): void
    {
        $this->assertTrue(
            class_exists(MicroweberFilamentThemeServiceProvider::class),
            'The service provider class should autoload standalone.'
        );
    }

    public function test_service_provider_source_file_exists(): void
    {
        $this->assertFileExists(
            dirname(__DIR__) . '/src/MicroweberFilamentThemeServiceProvider.php'
        );
    }

    public function test_palette_exposes_brand_blue_ladder(): void
    {
        $this->assertTrue(class_exists(Palette::class));
        $this->assertIsArray(Palette::Blue);

        // Full 50..950 tonal ladder.
        foreach ([50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950] as $shade) {
            $this->assertArrayHasKey($shade, Palette::Blue);
        }

        // 500 is Bootstrap brand blue #0d6efd expressed as an RGB triplet.
        $this->assertSame('13, 110, 253', Palette::Blue[500]);
    }
}
