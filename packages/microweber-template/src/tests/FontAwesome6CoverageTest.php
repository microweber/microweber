<?php

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Guards the Font Awesome 4.7 -> 6 upgrade.
 *
 * The site serves FA6 Free but its markup (and every stored icon value, and
 * icon_html's output) still uses the v4 "fa fa-X" class pair. That keeps working
 * ONLY because the vendored CSS bundles FA6 + the v4 backward-compat shims. This
 * test pins all three: the bundle is FA6, the shims are present, and every icon
 * name the site actually uses resolves (native FA6 class OR a v4 shim). It also
 * asserts a handful of FA6-only icons exist, so a regression to FA4 fails here.
 */
class FontAwesome6CoverageTest extends TestCase
{
    private static function faCss(): string
    {
        $path = dirname(__DIR__, 3)
            . '/frontend-assets-libs/resources/local-libs/font-awesome-6/css/font-awesome.css';
        if (!is_file($path)) {
            self::fail("Vendored FA6 CSS not found at {$path}");
        }
        return (string) file_get_contents($path);
    }

    /** An old icon renders under "fa fa-X" if it is a native FA6 class or a v4 shim. */
    private static function isCovered(string $css, string $name): bool
    {
        $n = preg_quote($name, '#');
        return (bool) preg_match('#\.fa\.' . $n . '\s*\{#', $css)   // v4 shim: .fa.fa-X {
            || (bool) preg_match('#(^|,|\})\s*\.' . $n . '\s*\{#m', $css); // native: .fa-X {
    }

    #[Test]
    public function the_bundle_is_font_awesome_6_with_v4_shims(): void
    {
        $css = self::faCss();
        $this->assertMatchesRegularExpression('/Font Awesome Free 6\.[0-9.]+/', $css,
            'The vendored font-awesome.css must be Font Awesome Free 6.x.');
        // v4-shim rules map old names, e.g. .fa.fa-dashboard / .fa.fa-glass
        $this->assertMatchesRegularExpression('/\.fa\.fa-(glass|dashboard|envelope-o)\s*\{/', $css,
            'The bundle must include the v4 backward-compat shims (.fa.fa-<old> rules).');
        // The flat webfont src (built layout) — no ../webfonts/ leakage.
        $this->assertStringNotContainsString('../webfonts/', $css,
            'Webfont urls must be rewritten to the flat served layout (./fa-*.woff2).');
    }

    /**
     * Every v4 icon name the site actually ships must resolve in FA6. If a future
     * icon is added to a template/module with a name FA6 lacks, this fails and the
     * author adds the FA6 name instead of shipping an empty box.
     */
    #[DataProvider('usedIconProvider')]
    #[Test]
    public function used_v4_icon_names_resolve_in_fa6(string $name): void
    {
        $this->assertTrue(
            self::isCovered(self::faCss(), $name),
            "Icon '{$name}' is used by the site but is not covered by FA6 (no native class and no v4 shim). "
            . "Use its FA6 name, or add a shim."
        );
    }

    public static function usedIconProvider(): array
    {
        // A representative slice of the icons actually used across templates, the
        // menu module, module defaults and icon_html output (audited from the repo).
        $icons = [
            'fa-rocket', 'fa-shield', 'fa-cloud', 'fa-globe', 'fa-users', 'fa-user',
            'fa-shopping-bag', 'fa-shopping-cart', 'fa-bolt', 'fa-line-chart',
            'fa-home', 'fa-search', 'fa-envelope', 'fa-phone', 'fa-star', 'fa-heart',
            'fa-check', 'fa-times', 'fa-bars', 'fa-cog', 'fa-pencil', 'fa-trash',
            'fa-calendar', 'fa-clock-o', 'fa-map-marker', 'fa-lock', 'fa-link',
            'fa-image', 'fa-play', 'fa-angle-down', 'fa-arrow-right', 'fa-plus',
        ];
        return array_map(fn ($i) => [$i], $icons);
    }

    /** FA6 brings icons FA4 never had — prove the richer set is really present. */
    #[DataProvider('fa6OnlyIconProvider')]
    #[Test]
    public function fa6_only_icons_are_available(string $name): void
    {
        $this->assertMatchesRegularExpression('#(^|,|\})\s*\.' . preg_quote($name, '#') . '\s*\{#m', self::faCss(),
            "FA6-only icon '{$name}' should be present in the bundle.");
    }

    public static function fa6OnlyIconProvider(): array
    {
        return array_map(fn ($i) => [$i], [
            'fa-diagram-project', 'fa-wand-magic-sparkles', 'fa-robot',
            'fa-circle-nodes', 'fa-bolt-lightning',
        ]);
    }
}
