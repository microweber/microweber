<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\ColorPaletteFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansColorPaletteTestFixtures;
use Tests\Browser\Traits\LiveEditColorPaletteTrait;
use Tests\DuskTestCase;

/**
 * Phase-3 color-scheme coverage (per-palette): sunset-boulevard.
 *
 * Applies the sunset-boulevard style-pack on :root via the same
 * `setPropertyForSelectorBulk` API the Vue picker calls on swatch
 * click, then snapshots every `--mw-*` variable on the canvas
 * iframe's `:root` and asserts the computed value matches the pack
 * JSON (modulo hex↔rgb normalization).
 *
 * Regressions this catches:
 *   - The picker silently failing to apply a pack (e.g. a JS error
 *     during `setPropertyForSelectorBulk` — the ui would still
 *     display the swatch as "active" but the canvas document
 *     wouldn't update, bleeding the previous palette through).
 *   - A pack-file edit that drops or renames a variable the
 *     template consumes.
 *   - cssEditor.setPropertyForSelectorBulk itself becoming lossy
 *     (e.g. skipping properties on a `!important` collision).
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
#[Group('color-palettes')]
class LiveEditColorPaletteSunsetBoulevardTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    private const PALETTE_SLUG = 'sunset-boulevard';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function sunset_boulevard_applies_every_css_custom_property_to_root(): void
    {
        $fixture = ColorPaletteFactory::make('sunset-boulevard apply');

        $packs = array_column($this->listColorPalettes(), null, 'slug');
        $this->assertArrayHasKey(
            self::PALETTE_SLUG,
            $packs,
            'sunset-boulevard pack must be discoverable on disk'
        );
        $pack = $packs[self::PALETTE_SLUG];
        $this->assertNotEmpty(
            $pack['properties'],
            "sunset-boulevard pack must declare at least one CSS variable"
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
                    "Expected core variable {$coreVar} on :root after sunset-boulevard apply"
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
