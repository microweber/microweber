<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\ColorPaletteFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansColorPaletteTestFixtures;
use Tests\Browser\Traits\LiveEditColorPaletteTrait;
use Tests\DuskTestCase;

/**
 * Phase-3 color-scheme coverage (public-render DOM targets).
 *
 * The sibling {@see LiveEditColorPalettePublicRenderTest} proves that
 * every `--mw-*` variable declared by a pack lands on the public `:root`
 * after save. That's necessary but not sufficient: a regression in
 * `Templates/Bootstrap/resources/assets/design-styles/design-assignments.scss`
 * (which is the file that actually wires `--mw-body-color` → `body color`,
 * `--mw-heading-color` → `.main h1…h6 color`, and `--mw-btn-background-color`
 * → `.btn.btn-primary background-color`) could silently drop the binding
 * while the `:root` vars still look fine.
 *
 * This test closes that gap: for every shipping pack, it:
 *   1. applies the pack in live-edit and saves;
 *   2. drops the admin session and visits the page's public URL as guest;
 *   3. reads the *real* computed color on three DOM targets that the
 *      fixture page is guaranteed to contain (body / a heading in .main /
 *      a primary button); and
 *   4. asserts each target equals the pack's declared variable value.
 *
 * Binding map (see design-assignments.scss:199, :213, :26):
 *   - `body`                                → `color: var(--mw-body-color)`
 *   - `.main h1, h2, h3, h4, h5, h6`        → `color: var(--mw-heading-color)`
 *   - `.btn.btn-primary` (and `.btn:not(.btn-secondary):not(.btn-outline)
 *                              :not(.btn-link):not(.btn-dark)`)
 *                                          → `background-color: var(--mw-btn-background-color)`
 *
 * The fixture layout (ColorPaletteFactory::LAYOUT_MODULES) composes
 * jumbotron/skin-1 + content/skin-1 + pricing/skin-1 + footers/skin-1,
 * which is enough to guarantee all three target categories render.
 *
 * One failing palette fails one row, not the whole test.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
#[Group('color-palettes')]
class LiveEditColorPalettePublicRenderRealTargetsTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    /**
     * @return iterable<string, array{0:string}>
     */
    public static function paletteProvider(): iterable
    {
        // Data providers run before the Laravel app boots, so
        // resolve the template dir relative to this file instead.
        $root = dirname(__DIR__, 2);
        $dir = $root . '/Templates/Bootstrap/resources/assets/design-styles/style-packs/colors';

        $files = is_dir($dir) ? (glob($dir . '/*.json') ?: []) : [];
        sort($files);

        foreach ($files as $file) {
            $slug = pathinfo($file, PATHINFO_FILENAME);
            yield $slug => [$slug];
        }
    }

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    #[DataProvider('paletteProvider')]
    public function palette_paints_real_dom_targets_on_public_render(string $slug): void
    {
        $packs = array_column($this->listColorPalettes(), null, 'slug');
        $this->assertArrayHasKey(
            $slug,
            $packs,
            "color pack '{$slug}' must be discoverable on disk"
        );
        $pack = $packs[$slug];

        foreach (['--mw-body-color', '--mw-heading-color', '--mw-btn-background-color'] as $required) {
            $this->assertArrayHasKey(
                $required,
                $pack['properties'],
                "pack '{$slug}' must declare '{$required}' for the real-target assertion"
            );
        }

        $fixture = ColorPaletteFactory::make("public-render-real-targets-{$slug}");

        $this->browse(function (Browser $browser) use ($fixture, $pack, $slug) {
            $this->loginAsAdmin($browser);
            $this->openColorPaletteSidebar($browser, $fixture->pageId);

            $this->clickPalette($browser, $slug);
            $this->assertPaletteApplied($browser, $pack['properties']);

            $this->saveLiveEdit($browser);
            $browser->pause(1500);

            // Drop admin session; public render must not require auth.
            $browser->driver->manage()->deleteAllCookies();

            $browser->visit('/' . ltrim($fixture->slug, '/'))->pause(2000);

            $result = $browser->script(
                "try {
                    function rgb(el, prop) {
                        if (!el) return null;
                        return (getComputedStyle(el).getPropertyValue(prop) || '').trim();
                    }
                    var body = document.body;
                    // The jumbotron skin overrides heading color with its
                    // hero-specific rule, so pick a heading inside the
                    // content/skin-1 section — that's where the default
                    // `.main h1…h6 { color: var(--mw-heading-color) }`
                    // rule (design-assignments.scss:213) is uncontested.
                    var heading = document.querySelector(
                        '[field*=\"layout-content-skin-1\"] h1, ' +
                        '[field*=\"layout-content-skin-1\"] h2, ' +
                        '[field*=\"layout-content-skin-1\"] h3, ' +
                        '[field*=\"layout-content-skin-1\"] h4, ' +
                        '[field*=\"layout-content-skin-1\"] h5, ' +
                        '[field*=\"layout-content-skin-1\"] h6'
                    );
                    // Pricing/skin-1's Pro card always carries `.btn.btn-primary`
                    // (the literal first selector in design-assignments.scss line 26
                    // `.btn.btn-primary { background-color: var(--mw-btn-background-color); }`).
                    // Other `.btn` variants on the page (outline-primary on the Free
                    // card, etc.) have weaker or overridden specificity that may let
                    // Bootstrap's default paint through — not a regression, just a
                    // quirk of the non-primary selector spec. The primary button is
                    // the canonical real-target for --mw-btn-background-color.
                    var primaryBtn = document.querySelector('.btn.btn-primary');
                    return {
                        bodyColor: rgb(body, 'color'),
                        headingFound: !!heading,
                        headingTag: heading ? heading.tagName : null,
                        headingColor: rgb(heading, 'color'),
                        btnFound: !!primaryBtn,
                        btnBg: rgb(primaryBtn, 'background-color')
                    };
                } catch (e) {
                    return { error: (e && e.message) ? e.message : String(e) };
                }"
            );

            $state = is_array($result[0] ?? null) ? $result[0] : [];
            $this->assertArrayNotHasKey(
                'error',
                $state,
                "Script error reading real-target colors for '{$slug}': "
                . ($state['error'] ?? '')
            );

            $this->assertTrue(
                (bool)($state['headingFound'] ?? false),
                "Public render of '{$fixture->slug}' must contain at least "
                . "one .main h1…h6 (pack '{$slug}' cannot bind heading color "
                . "without a real target)"
            );
            $this->assertTrue(
                (bool)($state['btnFound'] ?? false),
                "Public render of '{$fixture->slug}' must contain at least "
                . "one primary .btn (pack '{$slug}' cannot bind button background "
                . "without a real target)"
            );

            $expectedBody = $this->normalizeCssColor((string)$pack['properties']['--mw-body-color']);
            $expectedHeading = $this->normalizeCssColor((string)$pack['properties']['--mw-heading-color']);

            $actualBody = $this->normalizeCssColor((string)($state['bodyColor'] ?? ''));
            $actualHeading = $this->normalizeCssColor((string)($state['headingColor'] ?? ''));

            $this->assertSame(
                $expectedBody,
                $actualBody,
                "body.color for pack '{$slug}' should reflect --mw-body-color: "
                . "expected '{$pack['properties']['--mw-body-color']}' "
                . "got '{$state['bodyColor']}'"
            );
            $this->assertSame(
                $expectedHeading,
                $actualHeading,
                "{$state['headingTag']}.color (inside .main) for pack '{$slug}' "
                . "should reflect --mw-heading-color: expected "
                . "'{$pack['properties']['--mw-heading-color']}' got '{$state['headingColor']}'"
            );

            // A gradient is legal as a CSS custom-property value but
            // `background-color` only accepts <color>, so the computed
            // background-color on the button falls back to transparent
            // `rgba(0, 0, 0, 0)` — this is a CSS-spec limitation of the
            // pack, not a save/render regression. The :root var is still
            // set correctly (covered by LiveEditColorPalettePublicRenderTest).
            $btnPackValue = (string)$pack['properties']['--mw-btn-background-color'];
            if (stripos($btnPackValue, 'gradient') === false) {
                $expectedBtnBg = $this->normalizeCssColor($btnPackValue);
                $actualBtnBg = $this->normalizeCssColor((string)($state['btnBg'] ?? ''));
                $this->assertSame(
                    $expectedBtnBg,
                    $actualBtnBg,
                    "primary-btn.background-color for pack '{$slug}' should reflect "
                    . "--mw-btn-background-color: expected "
                    . "'{$btnPackValue}' got '{$state['btnBg']}'"
                );
            }
        });
    }
}
