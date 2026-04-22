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
 * Phase-5 palette-switch bleed safety.
 *
 * When a user clicks palette B after palette A, every `--mw-*` variable
 * that B declares must end up with B's value on `:root`. A's value must
 * not linger on any property B defines, and A-only properties (none
 * currently exist — the 17 packs share an identical key set — but the
 * test is shape-tolerant to future divergence) must not keep A's value
 * if B's value for the shared property would override them.
 *
 * Why apple-shine → neon-night:
 *   - Both packs declare the same 25 `--mw-*` properties, so B reliably
 *     overwrites every one A set. This is maximal coverage for a single
 *     A/B pair.
 *   - For ~22 of the 25 keys the two packs ship *different* hex values,
 *     so a silent bleed (e.g. `cssEditor.setPropertyForSelectorBulk`
 *     skipping a property that already has a value) is visible as an
 *     A-matching computed value after the B apply. The handful of keys
 *     that happen to agree (btn text/hover = #ffffff in both, etc.)
 *     still pass the B-equality check — they just can't distinguish a
 *     bleed from a legitimate match.
 *   - These slugs are in the Phase-1 per-palette suite so any test-wide
 *     regression in `clickPalette()` would show up on those first.
 *
 * Binding path exercised:
 *   - Driving {@see LiveEditColorPaletteTrait::clickPalette()} twice
 *     invokes `mw.top().app.cssEditor.setPropertyForSelectorBulk(':root',
 *     props, true, true)` twice, which is exactly what FieldStylePack.vue
 *     calls on click. A regression in bulk-apply merge semantics (e.g.
 *     clobbering the new value with a cached undo frame) would land here.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditColorPaletteSwitchNoBleedTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    private const PALETTE_A = 'apple-shine';
    private const PALETTE_B = 'neon-night';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function switching_from_apple_shine_to_neon_night_leaves_no_bleed_on_root(): void
    {
        $packs = array_column($this->listColorPalettes(), null, 'slug');
        $this->assertArrayHasKey(
            self::PALETTE_A,
            $packs,
            "palette A '" . self::PALETTE_A . "' must be discoverable on disk"
        );
        $this->assertArrayHasKey(
            self::PALETTE_B,
            $packs,
            "palette B '" . self::PALETTE_B . "' must be discoverable on disk"
        );

        $packA = $packs[self::PALETTE_A];
        $packB = $packs[self::PALETTE_B];

        $this->assertNotEmpty(
            $packA['properties'],
            'palette A must declare at least one CSS variable'
        );
        $this->assertNotEmpty(
            $packB['properties'],
            'palette B must declare at least one CSS variable'
        );

        $fixture = ColorPaletteFactory::make('switch-no-bleed');

        $this->browse(function (Browser $browser) use ($fixture, $packA, $packB) {
            $this->loginAsAdmin($browser);
            $this->openColorPaletteSidebar($browser, $fixture->pageId);

            // Step 1 — Apply palette A, snapshot, assert A fully applied.
            $this->clickPalette($browser, self::PALETTE_A);
            $this->assertPaletteApplied($browser, $packA['properties']);
            $afterA = $this->snapshotRootCssVars($browser);

            // Step 2 — Apply palette B on top of A.
            $this->clickPalette($browser, self::PALETTE_B);
            $afterB = $this->snapshotRootCssVars($browser);

            // Check 1: every B-declared property must be present on :root
            // and carry B's value. A bleed (A's value lingering) would
            // flip this, since A and B differ on most keys.
            foreach ($packB['properties'] as $prop => $expected) {
                $this->assertArrayHasKey(
                    $prop,
                    $afterB,
                    "After switch A→B, :root is missing '{$prop}' that palette B declares"
                );
                $this->assertSame(
                    $this->normalizeCssColor((string)$expected),
                    $this->normalizeCssColor((string)$afterB[$prop]),
                    "After switch A→B, :root '{$prop}' must equal B's value "
                    . "'{$expected}' but got '{$afterB[$prop]}' "
                    . "(A had '" . ($afterA[$prop] ?? '<unset>') . "' — "
                    . "this looks like a palette-A bleed)"
                );
            }

            // Check 2: for the subset of keys where A and B disagree, the
            // post-switch value must NOT equal A's value. This is the
            // strict anti-bleed assertion — if setPropertyForSelectorBulk
            // ever short-circuited on "value already set" it would hold
                // A's value and the previous check could still coincidentally
            // match if A and B happened to agree on that key.
            foreach ($packB['properties'] as $prop => $bVal) {
                if (!array_key_exists($prop, $packA['properties'])) {
                    continue;
                }
                $aVal = (string)$packA['properties'][$prop];
                $bStr = (string)$bVal;
                if ($this->normalizeCssColor($aVal) === $this->normalizeCssColor($bStr)) {
                    continue;
                }

                $got = $this->normalizeCssColor((string)($afterB[$prop] ?? ''));
                $this->assertNotSame(
                    $this->normalizeCssColor($aVal),
                    $got,
                    "After switch A→B, :root '{$prop}' still holds palette A's "
                    . "value '{$aVal}' — palette-switch bleed detected"
                );
            }

            // Check 3: any property A declared that B also declares (all
            // 25 currently) must not carry A's *old* value when B has a
            // different one — redundant with Check 2 but asserted against
            // the captured $afterA snapshot to guard against a snapshot-
            // shift regression in `snapshotRootCssVars()` itself.
            foreach ($packA['properties'] as $prop => $aVal) {
                if (!array_key_exists($prop, $packB['properties'])) {
                    continue;
                }
                $bVal = (string)$packB['properties'][$prop];
                if ($this->normalizeCssColor((string)$aVal) === $this->normalizeCssColor($bVal)) {
                    continue;
                }
                $this->assertArrayHasKey(
                    $prop,
                    $afterA,
                    "Expected A's snapshot to contain '{$prop}' before switch"
                );
                $this->assertNotSame(
                    $this->normalizeCssColor((string)($afterA[$prop] ?? '')),
                    $this->normalizeCssColor((string)($afterB[$prop] ?? '')),
                    "After switch A→B, :root '{$prop}' still matches its A-era "
                    . "snapshot value '" . ($afterA[$prop] ?? '') . "' — "
                    . "palette-switch bleed detected"
                );
            }
        });
    }
}
