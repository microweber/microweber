<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\ColorPaletteFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansColorPaletteTestFixtures;
use Tests\Browser\Traits\LiveEditColorPaletteTrait;
use Tests\DuskTestCase;

/**
 * Phase-3 color-scheme coverage (per-palette): apple-shine.
 *
 * Applies the apple-shine style-pack on :root via the same
 * `setPropertyForSelectorBulk` API the Vue picker calls on swatch
 * click, then snapshots every `--mw-*` variable on the top document's
 * `:root` and asserts the computed value matches the pack JSON
 * (modulo hex↔rgb normalization).
 *
 * Regressions this catches:
 *   - The picker silently failing to apply a pack (e.g. a JS error
 *     during `setPropertyForSelectorBulk` — the ui would still
 *     display the swatch as "active" but the document wouldn't
 *     update, bleeding the previous palette through).
 *   - A pack-file edit that drops or renames a variable the
 *     template consumes (would surface as the core-variable
 *     assertion in the unit-suite, but a browser-level check
 *     proves the computed-style side also receives every one).
 *   - cssEditor.setPropertyForSelectorBulk itself becoming lossy
 *     (e.g. skipping properties on a `!important` collision).
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditColorPaletteAppleShineTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    private const PALETTE_SLUG = 'apple-shine';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function apple_shine_applies_every_css_custom_property_to_root(): void
    {
        $fixture = ColorPaletteFactory::make('apple-shine apply');

        $packs = array_column($this->listColorPalettes(), null, 'slug');
        $this->assertArrayHasKey(
            self::PALETTE_SLUG,
            $packs,
            'apple-shine pack must be discoverable on disk'
        );
        $pack = $packs[self::PALETTE_SLUG];
        $this->assertNotEmpty(
            $pack['properties'],
            "apple-shine pack must declare at least one CSS variable"
        );

        $this->browse(function (Browser $browser) use ($fixture, $pack) {
            $this->loginAsAdmin($browser);
            $this->openColorPaletteSidebar($browser, $fixture->pageId);

            $before = $this->snapshotRootCssVars($browser);
            $this->assertNotEmpty(
                $before,
                'Expected :root to expose --mw-* computed variables before apply'
            );

            $this->clickPalette($browser, self::PALETTE_SLUG);

            $this->assertPaletteApplied($browser, $pack['properties']);

            // Every core variable must be set even if the pack's own
            // property map is ever trimmed — double-guards the bleed
            // path that the unit-suite assertion also covers.
            $after = $this->snapshotRootCssVars($browser);
            foreach ([
                '--mw-background-color',
                '--mw-primary-color',
                '--mw-body-color',
                '--mw-heading-color',
                '--mw-paragraph-color',
                '--mw-link-color',
            ] as $coreVar) {
                $this->assertArrayHasKey(
                    $coreVar,
                    $after,
                    "Expected core variable {$coreVar} on :root after apple-shine apply"
                );
                $this->assertNotSame(
                    '',
                    trim((string)$after[$coreVar]),
                    "Core variable {$coreVar} must have a non-empty computed value after apply"
                );
            }
        });
    }
}
