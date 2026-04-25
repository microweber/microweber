<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\ColorPaletteSkinMatrixFactory;
use Tests\TestCase;

/**
 * Plan D.2 third bullet — matrix-side drift guard.
 *
 * The sibling
 * {@see ColorPaletteSkinMatrixFactoryTargetSkinsContractTest}
 * checks one direction of the contract: every per-skin Dusk
 * test has a matching entry in
 * {@see ColorPaletteSkinMatrixFactory::TARGET_SKINS}. Without it,
 * a contributor who adds a new per-skin test could forget to
 * add the skin to the matrix constant and the cross-skin palette
 * matrix would silently skip the new skin.
 *
 * This test guards the OTHER direction — Plan D.2's third bullet:
 *
 *   "Add a matrix drift test LiveEditColorPaletteTargetSkinDriftTest
 *    that asserts ColorPaletteSkinMatrixFactory::TARGET_SKINS stays
 *    in sync with the actual blade files in
 *    Templates/Bootstrap/resources/views/modules/layouts/templates/
 *    — silently-missing skins are the biggest miss risk."
 *
 * The biggest miss risk that this test catches: a contributor
 * lands a brand-new layout skin blade (e.g. `cta/skin-1.blade.php`),
 * the public storefront immediately starts rendering it, but
 * `TARGET_SKINS` never gains the entry. The cross-skin palette
 * matrix silently skips the skin and a regression in any pack's
 * paint-on-cta-skin-1 stays uncaught indefinitely.
 *
 * Mechanism: glob every blade under the bootstrap layouts skins
 * tree, filter to the canonical `<family>/skin-<N>` shape used
 * by `TARGET_SKINS`, and assert each shipped tag is either:
 *
 *   1. PRESENT in `TARGET_SKINS` (the matrix covers it), OR
 *   2. EXPLICITLY excluded by this test (a documented opt-out).
 *
 * Pending-stub carve-out:
 *   `TARGET_SKINS` may include skins whose blade isn't shipped
 *   yet (Plan B.2 forward-compat — `availableSkins()` filters
 *   them out at runtime). That direction is fine and covered by
 *   the other contract test; here we only flag blades that exist
 *   on disk but aren't in the constant.
 *
 * Lives under tests/Feature/ to inherit the Laravel app boot;
 * only reads files, no DB, no HTTP.
 */
class LiveEditColorPaletteTargetSkinDriftTest extends TestCase
{
    /**
     * Layout-skin blades whose absence from `TARGET_SKINS` is
     * documented + intentional. Keep this list short and add a
     * comment for every entry so the next contributor knows why.
     *
     * Today: empty. Every layout-skin blade we ship is matrix-
     * covered. If a new excluded shape is needed (e.g. a private
     * "internal-only" skin used in tests but not the storefront),
     * add it here with a comment explaining why the matrix should
     * skip it.
     *
     * @var list<string>
     */
    private const DOCUMENTED_EXCLUSIONS = [];

    /**
     * Discover every layout-skin blade shipped under the
     * Bootstrap template's layout-skins tree and derive the
     * canonical `<family>/skin-<N>` tag used by `TARGET_SKINS`.
     *
     * Skin tags are derived by walking
     * `Templates/Bootstrap/resources/views/modules/layouts/templates/`
     * and collecting every `<family>/skin-<N>.blade.php` match.
     * Other blades in the tree (default.blade.php, 404.blade.php,
     * partials/*, content/*) are filtered out.
     *
     * @return list<string>
     */
    private function discoverShippedSkinTags(): array
    {
        $base = base_path(
            'Templates/Bootstrap/resources/views/modules/layouts/templates'
        );

        if (!is_dir($base)) {
            return [];
        }

        $tags = [];
        $iter = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $base,
                \FilesystemIterator::SKIP_DOTS
            )
        );

        foreach ($iter as $path => $info) {
            if (!$info->isFile()) {
                continue;
            }
            // Only `<family>/skin-<N>.blade.php` — exclude
            // top-level helpers (default.blade.php, 404.blade.php)
            // and any deeper `<family>/<sub>/<file>.blade.php`
            // partials.
            $rel = ltrim(
                substr((string) $path, strlen($base)),
                DIRECTORY_SEPARATOR
            );
            $rel = str_replace(DIRECTORY_SEPARATOR, '/', $rel);

            if (!preg_match(
                '#^([a-z0-9][a-z0-9\-]*)/skin-(\d+)\.blade\.php$#',
                $rel,
                $m
            )) {
                continue;
            }

            $tags[] = sprintf('%s/skin-%d', $m[1], (int) $m[2]);
        }

        sort($tags);
        return array_values(array_unique($tags));
    }

    #[Test]
    public function discover_shipped_skins_returns_known_families(): void
    {
        // Pin the discovery rule so a future refactor that breaks
        // the regex (e.g. allows skin-N with leading zeros, or
        // misses compound family names) fails this test, not the
        // bigger contract test below (where the failure mode would
        // be confusingly indirect).
        $shipped = $this->discoverShippedSkinTags();

        $this->assertNotEmpty(
            $shipped,
            'Expected at least one shipped layout-skin blade under '
            . 'Templates/Bootstrap/resources/views/modules/layouts/templates/. '
            . 'Either the discovery walker is broken or the bootstrap template '
            . 'lost every skin file (which would itself be a regression).'
        );

        // Spot-check a couple of stable canonical tags so a
        // regression in the regex (kebab handling, digit handling)
        // surfaces here, not in the bigger drift assertion.
        $this->assertContains(
            'jumbotron/skin-1',
            $shipped,
            'The canonical `jumbotron/skin-1` blade is the oldest in the tree '
            . '— if discovery fails to find it, the regex / walker has '
            . 'fundamentally regressed.'
        );
    }

    #[Test]
    public function every_shipped_skin_blade_has_a_matching_target_skins_entry(): void
    {
        $shipped = $this->discoverShippedSkinTags();
        $covered = ColorPaletteSkinMatrixFactory::TARGET_SKINS;
        $excluded = self::DOCUMENTED_EXCLUSIONS;

        $missing = [];
        foreach ($shipped as $tag) {
            if (in_array($tag, $covered, true)) {
                continue;
            }
            if (in_array($tag, $excluded, true)) {
                continue;
            }
            $missing[] = $tag;
        }

        $this->assertSame(
            [],
            $missing,
            'Plan D.2 drift — these layout-skin blades are SHIPPED on disk but '
            . 'NOT in ColorPaletteSkinMatrixFactory::TARGET_SKINS, so the cross-'
            . "skin palette matrix (LiveEditColorPaletteSkinMatrixTest +\n"
            . 'LiveEditColorPaletteLayoutMatrixTest) is silently skipping them. '
            . 'A regression in any pack\'s paint-on-this-skin would stay '
            . "uncaught.\n\n"
            . 'Fix: add each tag to TARGET_SKINS so the matrix picks them up '
            . 'on the next run. If the skin should genuinely be skipped (e.g. '
            . 'a private internal-only blade), add it to '
            . 'DOCUMENTED_EXCLUSIONS in this test with a comment explaining '
            . "why.\n\n"
            . 'Drifted skin tags: ' . json_encode($missing, JSON_UNESCAPED_SLASHES)
        );
    }

    #[Test]
    public function documented_exclusions_actually_exist_on_disk(): void
    {
        // Stale exclusions are dangerous — they can silently
        // suppress a future drift signal. If someone adds a tag to
        // DOCUMENTED_EXCLUSIONS but later renames or removes the
        // blade, the exclusion becomes inert noise. This test
        // surfaces that drift.
        $shipped = $this->discoverShippedSkinTags();
        $stale = [];

        foreach (self::DOCUMENTED_EXCLUSIONS as $tag) {
            if (!in_array($tag, $shipped, true)) {
                $stale[] = $tag;
            }
        }

        $this->assertSame(
            [],
            $stale,
            'These DOCUMENTED_EXCLUSIONS no longer correspond to any shipped '
            . 'blade — the exclusion is suppressing nothing and should be '
            . 'removed from this test. Stale: '
            . json_encode($stale, JSON_UNESCAPED_SLASHES)
        );
    }
}
