<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-286 Responsive Grid contract test.
 *
 * Pins the six breakpoint tokens to specific px values in both
 * canonical design-system.css and the public-touch.css mirror, plus
 * pins the three new responsive utility classes
 * (.mw-stack-below-sm, .mw-hero-split, .mw-grid-shop) and the shop
 * Livewire blade's 1/2/3-col grid markup.
 *
 * Fails fast in unit CI if a future refactor renames a breakpoint,
 * drifts a value, drops a utility class, or breaks the shop grid
 * back to its pre-AI-286 1→2 jump (no intermediate 2-col state).
 */
class DesignSystemBreakpointsContractTest extends TestCase
{
    private const DESIGN_SYSTEM_CSS = __DIR__ . '/../../../packages/frontend-assets/resources/assets/css/microweber/css/design-system.css';
    private const PUBLIC_TOUCH_CSS = __DIR__ . '/../../../Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const SHOP_BLADE = __DIR__ . '/../../../Modules/Shop/resources/views/livewire/shop/default.blade.php';

    private const EXPECTED_BREAKPOINTS = [
        '--breakpoint-xs' => '0',
        '--breakpoint-sm' => '576px',
        '--breakpoint-md' => '768px',
        '--breakpoint-lg' => '992px',
        '--breakpoint-xl' => '1200px',
        '--breakpoint-xxl' => '1440px',
    ];

    #[Test]
    public function design_system_css_defines_six_breakpoint_tokens(): void
    {
        $css = $this->readFile(self::DESIGN_SYSTEM_CSS);

        foreach (self::EXPECTED_BREAKPOINTS as $token => $value) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($token, '/') . '\s*:\s*' . preg_quote($value, '/') . '\s*;/',
                $css,
                "design-system.css must define {$token}: {$value};"
            );
        }
    }

    #[Test]
    public function public_touch_css_mirrors_six_breakpoint_tokens(): void
    {
        $css = $this->readFile(self::PUBLIC_TOUCH_CSS);

        foreach (self::EXPECTED_BREAKPOINTS as $token => $value) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($token, '/') . '\s*:\s*' . preg_quote($value, '/') . '\s*;/',
                $css,
                "public-touch.css must mirror {$token}: {$value};"
            );
        }
    }

    #[Test]
    public function public_touch_css_defines_three_responsive_utility_classes(): void
    {
        $css = $this->readFile(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/\.mw-stack-below-sm\s*\{/',
            $css,
            'public-touch.css must define .mw-stack-below-sm utility class.'
        );
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*575\.98px\)\s*\{\s*\.mw-stack-below-sm/s',
            $css,
            '.mw-stack-below-sm rule must be gated by `@media (max-width: 575.98px)`.'
        );

        $this->assertMatchesRegularExpression(
            '/\.mw-hero-split\s*\{/',
            $css,
            'public-touch.css must define .mw-hero-split utility class.'
        );
        $this->assertMatchesRegularExpression(
            '/@media\s*\(min-width:\s*768px\)\s*\{\s*\.mw-hero-split/s',
            $css,
            '.mw-hero-split horizontal layout must apply from `@media (min-width: 768px)`.'
        );

        $this->assertMatchesRegularExpression(
            '/\.mw-grid-shop\s*\{/',
            $css,
            'public-touch.css must define .mw-grid-shop utility class.'
        );
    }

    #[Test]
    public function mw_grid_shop_uses_three_column_layout_at_lg_breakpoint(): void
    {
        $css = $this->readFile(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/@media\s*\(min-width:\s*992px\)\s*\{\s*\.mw-grid-shop\s*\{\s*grid-template-columns:\s*repeat\(3,\s*1fr\)\s*;/s',
            $css,
            '.mw-grid-shop must use repeat(3, 1fr) at the lg breakpoint (992px+).'
        );
        $this->assertMatchesRegularExpression(
            '/@media\s*\(min-width:\s*768px\)\s*\{\s*\.mw-grid-shop\s*\{\s*grid-template-columns:\s*repeat\(2,\s*1fr\)\s*;/s',
            $css,
            '.mw-grid-shop must use repeat(2, 1fr) at the md breakpoint (768px+).'
        );
    }

    #[Test]
    public function shop_livewire_blade_uses_1_2_3_responsive_grid(): void
    {
        $blade = $this->readFile(self::SHOP_BLADE);

        $this->assertMatchesRegularExpression(
            '/col-12\s+col-md-6\s+col-lg-4\s+col-xl-4/',
            $blade,
            'Shop Livewire blade must render product cards with `col-12 col-md-6 col-lg-4 col-xl-4` for 1→2→3-col responsive grid.'
        );
    }

    private function readFile(string $path): string
    {
        $real = realpath($path);
        $this->assertNotFalse($real, "File not found: {$path}");

        $contents = file_get_contents($real);
        $this->assertNotFalse($contents, "Could not read file: {$path}");
        $this->assertNotEmpty($contents, "File is empty: {$path}");

        return $contents;
    }
}
