<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Plan D.2 fourth bullet — per-pack public-render coverage contract.
 *
 * The Plan D.2 fourth-bullet task framing is:
 *
 *   "Per-palette public-render tests
 *    (LiveEditColorPalette<Pack>PublicRenderMatrixTest) already
 *    exist for some packs — ensure every pack in §D.1 has one."
 *
 * Today every shipped pack is covered through the data-provider
 * test {@see \Tests\Browser\LiveEditColorPalettePublicRenderTest}
 * — its `paletteProvider()` enumerates every JSON pack on disk
 * and yields one PHPUnit row per slug, so adding a pack
 * automatically gets per-pack coverage on the next dusk run.
 *
 * That data-provider arrangement is a stronger guarantee than
 * one-test-per-pack files because the provider can never drift
 * out of sync — there is exactly one place to update if the disk
 * inventory changes.
 *
 * This contract test pins that property in CI: the provider
 * MUST yield one row per shipped pack. A regression in the
 * provider's glob path, a typo'd extension filter, or a future
 * refactor that hardcodes a slug list would surface here as an
 * uncovered pack instead of as a silent miss in the next dusk
 * run.
 *
 * Lives under tests/Feature/ to inherit the Laravel app boot;
 * only reads files (no DB, no HTTP).
 */
class LiveEditColorPalettePerPackCoverageContractTest extends TestCase
{
    /**
     * Every JSON file under
     * `Templates/Bootstrap/resources/assets/design-styles/style-packs/colors/`
     * is a shipped color pack. The provider must yield one row
     * per slug here.
     *
     * @return list<string>
     */
    private function discoverShippedPackSlugs(): array
    {
        $dir = base_path(
            'Templates/Bootstrap/resources/assets/design-styles/style-packs/colors'
        );
        if (!is_dir($dir)) {
            return [];
        }

        $files = glob($dir . '/*.json') ?: [];
        $slugs = [];
        foreach ($files as $file) {
            $slugs[] = pathinfo($file, PATHINFO_FILENAME);
        }
        sort($slugs);
        return array_values(array_unique($slugs));
    }

    /**
     * Pull the slug list the public-render data provider would
     * yield. Resolves the same glob the provider uses; if the
     * provider ever rewires its discovery rule, this method must
     * mirror that rule so the contract stays accurate.
     *
     * @return list<string>
     */
    private function publicRenderProviderSlugs(): array
    {
        $slugs = [];
        $iter = \Tests\Browser\LiveEditColorPalettePublicRenderTest::paletteProvider();
        foreach ($iter as $key => $row) {
            $slugs[] = (string) $key;
        }
        sort($slugs);
        return array_values(array_unique($slugs));
    }

    #[Test]
    public function shipped_pack_inventory_is_non_empty(): void
    {
        $shipped = $this->discoverShippedPackSlugs();

        $this->assertNotEmpty(
            $shipped,
            'Expected at least one color pack on disk under '
            . 'Templates/Bootstrap/resources/assets/design-styles/style-packs/colors/. '
            . 'A regression that empties this directory would silently flip every '
            . 'palette test to a no-op (the data providers iterate the disk, so an '
            . 'empty disk yields zero rows).'
        );
    }

    #[Test]
    public function public_render_provider_yields_one_row_per_shipped_pack(): void
    {
        $shipped = $this->discoverShippedPackSlugs();
        $covered = $this->publicRenderProviderSlugs();

        $missingFromProvider = array_values(array_diff($shipped, $covered));
        $extraInProvider = array_values(array_diff($covered, $shipped));

        $this->assertSame(
            [],
            $missingFromProvider,
            'Plan D.2 drift — these shipped color packs are NOT covered by '
            . 'LiveEditColorPalettePublicRenderTest::paletteProvider(), so a '
            . "regression in their public-render save round-trip would stay\n"
            . "uncaught. Missing: "
            . json_encode($missingFromProvider, JSON_UNESCAPED_SLASHES)
            . "\n\nFix: the provider iterates the disk inventory, so a missing "
            . 'pack here means either the disk file is named oddly (the regex '
            . 'requires .json suffix and a slug-style basename) or the provider '
            . "glob rule has regressed. Inspect tests/Browser/"
            . 'LiveEditColorPalettePublicRenderTest.php::paletteProvider().'
        );

        $this->assertSame(
            [],
            $extraInProvider,
            'Plan D.2 drift — these slugs are yielded by the public-render '
            . "provider but no matching JSON pack exists on disk:\n"
            . json_encode($extraInProvider, JSON_UNESCAPED_SLASHES)
            . "\n\nFix: either restore the missing pack JSON file under "
            . 'Templates/Bootstrap/resources/assets/design-styles/style-packs/colors/, '
            . 'or update the provider so it stops yielding the orphaned slug. '
            . "Stale slugs cause the provider to yield rows whose pack['properties'] "
            . 'lookup will fail downstream with confusing "color pack must be '
            . "discoverable on disk\" errors."
        );
    }

    #[Test]
    public function provider_yield_count_matches_shipped_count_exactly(): void
    {
        // Belt-and-braces: even if the array_diff assertions above
        // are individually green, a duplicate yield could pass them
        // while leaving uneven coverage. Pin the cardinality
        // explicitly.
        $shipped = $this->discoverShippedPackSlugs();
        $covered = $this->publicRenderProviderSlugs();

        $this->assertSame(
            count($shipped),
            count($covered),
            'Plan D.2 drift — the public-render provider must yield exactly one '
            . "row per shipped pack. Yielded {$this->countLabel(count($covered))} "
            . "but disk inventory reports {$this->countLabel(count($shipped))} packs. "
            . 'A duplicate yield (same slug twice) or a missing slug both surface '
            . 'as a count mismatch here.'
        );
    }

    private function countLabel(int $n): string
    {
        return $n . ' pack' . ($n === 1 ? '' : 's');
    }
}
