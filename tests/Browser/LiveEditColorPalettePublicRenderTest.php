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
 * Phase-3 color-scheme coverage (public-render parity).
 *
 * For every Bootstrap color style-pack shipped under
 * `Templates/Bootstrap/resources/assets/design-styles/style-packs/colors/`,
 * this test:
 *
 *   1. creates a fresh color-palette fixture page (admin+Bootstrap+clean
 *      layout, via {@see ColorPaletteFactory});
 *   2. opens it in live-edit, applies the pack on `:root` via the same
 *      `setPropertyForSelectorBulk` API the Vue picker drives;
 *   3. saves — this fires `cssEditor.publishIfChanged()` →
 *      `/api/current_template_save_custom_css`, persisting the pack
 *      as the active template's custom CSS (see
 *      {@see \MicroweberPackages\Template\Adapters\TemplateCustomCss});
 *   4. deletes browser cookies (drops the admin session) and visits
 *      the page's public URL as a guest;
 *   5. snapshots `document.documentElement`'s computed `--mw-*` custom
 *      properties and asserts each one declared in the pack matches
 *      (hex↔rgb normalized).
 *
 * Why this is a data-provider, not a loop:
 *   Per task framing ("one failing palette fails one row, not the whole
 *   test"), each pack is its own PHPUnit dataset. If `neon-night`
 *   regresses the public render while 16 others survive, exactly one
 *   row fails and the diagnostic identifies the slug.
 *
 * Regressions this catches that the per-palette apply-to-:root tests do not:
 *   - The apply-to-:root tests only prove the *canvas iframe* receives the
 *     CSS — they stop before save. A bug in `cssEditor.publishIfChanged`
 *     (e.g. silently failing the XHR), in `TemplateCustomCss`'s disk
 *     write, or in the frontend's custom-css injection path would
 *     escape those tests but be caught here.
 *   - The template custom CSS is injected into every public render of
 *     the active template — so a regression in `getCustomCssUrl()` or
 *     its cache-invalidation layer would also surface.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
#[Group('color-palettes')]
class LiveEditColorPalettePublicRenderTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    /**
     * Yield one row per slug on disk. Named datasets let PHPUnit filter
     * by slug (e.g. `--filter='#apple-shine'`) and make failure output
     * self-describing.
     *
     * @return iterable<string, array{0:string}>
     */
    public static function paletteProvider(): iterable
    {
        // Data providers run before the Laravel app boots, so helpers
        // like base_path() aren't available — resolve relative to this
        // file instead. tests/Browser/LiveEditColorPalettePublicRenderTest.php
        // lives two dirs deep from the repo root.
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
    public function palette_survives_save_to_public_render(string $slug): void
    {
        $packs = array_column($this->listColorPalettes(), null, 'slug');
        $this->assertArrayHasKey(
            $slug,
            $packs,
            "color pack '{$slug}' must be discoverable on disk"
        );
        $pack = $packs[$slug];
        $this->assertNotEmpty(
            $pack['properties'],
            "color pack '{$slug}' must declare at least one CSS variable"
        );

        $fixture = ColorPaletteFactory::make("public-render-{$slug}");

        $this->browse(function (Browser $browser) use ($fixture, $pack, $slug) {
            $this->loginAsAdmin($browser);
            $this->openColorPaletteSidebar($browser, $fixture->pageId);

            $this->clickPalette($browser, $slug);
            $this->assertPaletteApplied($browser, $pack['properties']);

            $this->saveLiveEdit($browser);
            $browser->pause(1500);

            // Drop admin session; public render must not require auth.
            // Cache is invalidated on the next browse() via the teardown
            // trait which flips DuskTestCase::$adminLoggedIn = false.
            $browser->driver->manage()->deleteAllCookies();

            $browser->visit('/' . ltrim($fixture->slug, '/'))->pause(2000);

            $result = $browser->script(
                "try {
                    var styles = getComputedStyle(document.documentElement);
                    var out = {};
                    for (var i = 0; i < styles.length; i++) {
                        var prop = styles[i];
                        if (prop && prop.indexOf('--mw-') === 0) {
                            out[prop] = (styles.getPropertyValue(prop) || '').trim();
                        }
                    }
                    return out;
                } catch (e) {
                    return {};
                }"
            );

            $actual = is_array($result[0] ?? null) ? $result[0] : [];
            $this->assertNotEmpty(
                $actual,
                "Public render of '{$fixture->slug}' must expose --mw-* "
                . "custom properties on :root (pack: {$slug})"
            );

            foreach ($pack['properties'] as $prop => $expectedValue) {
                $this->assertArrayHasKey(
                    $prop,
                    $actual,
                    "Public :root is missing '{$prop}' for pack '{$slug}' "
                    . "— template custom CSS save did not persist"
                );

                $exp = $this->normalizeCssColor((string)$expectedValue);
                $got = $this->normalizeCssColor((string)$actual[$prop]);

                $this->assertSame(
                    $exp,
                    $got,
                    "Public :root '{$prop}' mismatch for pack '{$slug}': "
                    . "expected '{$expectedValue}' got '{$actual[$prop]}'"
                );
            }
        });
    }
}
