<?php

declare(strict_types=1);

namespace MicroweberPackages\SvgIcons\Tests\Unit;

use MicroweberPackages\SvgIcons\SvgIconsServiceProvider;
use MicroweberPackages\SvgIcons\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * Verifies that every shipped SVG can be rendered via the Blade @svg
 * directive using the 'mw-<name>' component name.
 */
class IconRenderingTest extends TestCase
{
    #[Test]
    #[DataProvider('iconProvider')]
    public function icon_renders_via_blade_svg_directive(string $icon): void
    {
        $blade = "@svg('mw-{$icon}', 'h-6 w-6')";

        $html = \Illuminate\Support\Facades\Blade::render($blade);

        $this->assertNotEmpty($html, "Rendering mw-{$icon} returned empty output.");
        $this->assertStringContainsString('<svg', $html, "Rendering mw-{$icon} did not produce an <svg> tag.");
    }

    #[Test]
    #[DataProvider('iconProvider')]
    public function icon_renders_via_blade_component(string $icon): void
    {
        $blade = '<x-mw-' . $icon . ' class="h-6 w-6" />';

        $html = \Illuminate\Support\Facades\Blade::render($blade);

        $this->assertNotEmpty($html, "Component <x-mw-{$icon}> returned empty output.");
        $this->assertStringContainsString('<svg', $html, "Component <x-mw-{$icon}> did not produce an <svg> tag.");
    }

    /**
     * Provides every icon name from the package.
     *
     * @return iterable<string, array{string}>
     */
    public static function iconProvider(): iterable
    {
        foreach (SvgIconsServiceProvider::availableIcons() as $icon) {
            yield $icon => [$icon];
        }
    }
}
