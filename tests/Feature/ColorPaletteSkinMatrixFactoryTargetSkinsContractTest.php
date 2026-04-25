<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\ColorPaletteSkinMatrixFactory;
use Tests\TestCase;

/**
 * Plan B.4 first-bullet contract — pin the relationship between
 * {@see ColorPaletteSkinMatrixFactory::TARGET_SKINS} and the
 * per-skin Dusk tests under tests/Browser/LiveEdit*Skin*Test.php.
 *
 * The Plan B.4 first-bullet task framing is:
 *   "Keep `LiveEditColorPaletteSkinMatrixTest` green across new
 *    skins; updating the factory's `TARGET_SKINS` constant is the
 *    hook point."
 *
 * The risk this test guards against: a future contributor lands a
 * new `LiveEdit<Family>Skin<N>Test` without remembering to update
 * `TARGET_SKINS`, and the cross-skin palette matrix silently
 * stops covering that skin. The matrix test itself wouldn't fail
 * in that scenario — `availableSkins()` only iterates what's IN
 * `TARGET_SKINS`, so a skin that's missing from the constant just
 * goes uncovered. This test surfaces that drift loudly.
 *
 * Contract:
 *   For every full-cycle per-skin Dusk test discovered under
 *   tests/Browser/LiveEdit*Skin*Test.php (with a pending-stub
 *   carve-out matching the JumbotronSkin2 pattern), the matching
 *   `<family>/<skin>` tag MUST be in `TARGET_SKINS`.
 *
 * Pending-stub carve-out:
 *   `LiveEditJumbotronSkin2Test` is a Plan-B.2 pending-skin stub
 *   (its blade isn't shipped yet — see {@see ColorPaletteSkinMatrixFactory::pendingSkins}).
 *   Its skin tag IS in `TARGET_SKINS` already (so when the blade
 *   lands the matrix picks it up automatically), but if a future
 *   contributor deletes the pending stub before shipping the
 *   blade, this test would flip to also requiring the stub. To
 *   stay robust, we derive the expected skin set from the per-skin
 *   test files via filename → skin-tag inference, then verify
 *   each inferred tag is present in `TARGET_SKINS`. Stub absence
 *   is irrelevant; what matters is each shipped test has matrix
 *   coverage.
 *
 * Lives under tests/Feature/ to inherit the Laravel app boot the
 * sibling trait tests use; only reads files, no DB, no HTTP.
 */
class ColorPaletteSkinMatrixFactoryTargetSkinsContractTest extends TestCase
{
    /**
     * Map a per-skin Dusk test class basename to its skin tag.
     * Filename pattern: `LiveEdit<Family><SkinN>Test`. The family
     * segment can be camelCase compounds (TextBlock, Ecommerce);
     * the SkinN segment is always the literal "Skin" + digit.
     *
     * Returned tags use the canonical `<family-kebab>/skin-<N>`
     * shape used everywhere in TARGET_SKINS / blade paths.
     */
    private function inferSkinTagFromTestClass(string $basename): ?string
    {
        if (! preg_match('/^LiveEdit(.+)Skin(\d+)Test$/', $basename, $m)) {
            return null;
        }

        $family = $m[1];
        $n = (int) $m[2];

        $kebab = strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1', $family));

        return sprintf('%s/skin-%d', $kebab, $n);
    }

    #[Test]
    public function inferred_skin_tag_round_trips_known_families(): void
    {
        // Pin the family-name → kebab-tag derivation so a future
        // refactor that breaks compound-word handling fails this
        // test, not the bigger contract test below (where the
        // failure mode would be confusingly indirect).
        $this->assertSame('jumbotron/skin-1', $this->inferSkinTagFromTestClass('LiveEditJumbotronSkin1Test'));
        $this->assertSame('features/skin-2', $this->inferSkinTagFromTestClass('LiveEditFeaturesSkin2Test'));
        $this->assertSame('text-block/skin-1', $this->inferSkinTagFromTestClass('LiveEditTextBlockSkin1Test'));
        $this->assertSame('pricing/skin-3', $this->inferSkinTagFromTestClass('LiveEditPricingSkin3Test'));
        $this->assertSame('menus/skin-1', $this->inferSkinTagFromTestClass('LiveEditMenusSkin1Test'));
    }

    #[Test]
    public function every_per_skin_dusk_test_has_a_matching_target_skins_entry(): void
    {
        $browserTestsDir = base_path('tests/Browser');
        $files = glob($browserTestsDir . '/LiveEdit*Skin*Test.php') ?: [];

        $perSkinTags = [];
        foreach ($files as $file) {
            $basename = pathinfo($file, PATHINFO_FILENAME);

            // Skip the matrix tests themselves (LiveEditColorPaletteSkinMatrix*Test).
            if (str_contains($basename, 'Matrix')) {
                continue;
            }

            $tag = $this->inferSkinTagFromTestClass($basename);
            if ($tag === null) {
                continue;
            }

            $perSkinTags[$tag] = $basename;
        }

        $this->assertNotEmpty(
            $perSkinTags,
            'Expected at least one per-skin Dusk test under tests/Browser/LiveEdit*Skin*Test.php; '
            . 'the glob returned no usable files. Either the directory layout changed or '
            . 'the inference rule is broken.'
        );

        $missing = [];
        foreach ($perSkinTags as $tag => $basename) {
            if (! in_array($tag, ColorPaletteSkinMatrixFactory::TARGET_SKINS, true)) {
                $missing[$tag] = $basename;
            }
        }

        $this->assertSame(
            [],
            $missing,
            'Plan B.4 first-bullet drift — these per-skin Dusk tests have no matching '
            . 'entry in ColorPaletteSkinMatrixFactory::TARGET_SKINS, so the cross-skin '
            . 'palette matrix is silently skipping them. Add each tag to TARGET_SKINS '
            . '(the documented hook point): '
            . json_encode($missing, JSON_UNESCAPED_SLASHES)
        );
    }

    #[Test]
    public function target_skins_has_no_duplicate_entries(): void
    {
        $tags = ColorPaletteSkinMatrixFactory::TARGET_SKINS;
        $this->assertSame(
            count($tags),
            count(array_unique($tags)),
            'TARGET_SKINS must not contain duplicate skin tags — a duplicate would make the '
            . 'matrix iterate the same fixture twice. Duplicates: '
            . json_encode(array_diff_assoc($tags, array_unique($tags)), JSON_UNESCAPED_SLASHES)
        );
    }
}
