<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\ColorPaletteSkinMatrixFactory;
use Tests\TestCase;

/**
 * Plan B.4 second-bullet contract — pin the relationship between
 * the positive-paint matrix ({@see \Tests\Browser\LiveEditColorPaletteSkinMatrixTest})
 * and the leak-proof matrix ({@see \Tests\Browser\LiveEditColorPaletteSkinMatrixNoLeakTest}).
 *
 * Plan B.4 second-bullet task framing:
 *   "Keep `LiveEditColorPaletteSkinMatrixNoLeakTest` green after
 *    any new skin test lands (proves the matrix is leak-proof per
 *    skin)."
 *
 * The risk this test guards against:
 *   The two matrix tests share `ColorPaletteSkinMatrixFactory::makeAll()`
 *   (which iterates `availableSkins()`) as their fixture source today.
 *   If a future contributor refactors either test to scope its
 *   fixture set to a smaller list (or hardcode skins inline), the
 *   leak-proof gate would silently stop matching the positive-paint
 *   gate's coverage. A new skin landing in TARGET_SKINS could then
 *   pass the positive matrix while leaking under apple-shine→neon-night
 *   without any test surfacing the regression.
 *
 * Contract: both matrix tests MUST consume
 * `ColorPaletteSkinMatrixFactory::makeAll()` so they iterate the same
 * skin set that's pinned by the Plan B.4 first-bullet contract.
 *
 * Lives under tests/Feature/ alongside the other matrix-factory
 * contract test; only reads files, no DB, no HTTP.
 */
class ColorPaletteSkinMatrixNoLeakCoverageContractTest extends TestCase
{
    /**
     * The two Dusk tests that must share fixture-iteration scope.
     * If a future Plan-D matrix lands a third matrix test, add it
     * here so the parity contract widens with the plan.
     *
     * @var list<string>
     */
    private const SHARED_FIXTURE_MATRIX_TESTS = [
        'tests/Browser/LiveEditColorPaletteSkinMatrixTest.php',
        'tests/Browser/LiveEditColorPaletteSkinMatrixNoLeakTest.php',
    ];

    #[Test]
    public function both_matrix_tests_consume_the_shared_factory_makeAll(): void
    {
        $missingConsumers = [];
        foreach (self::SHARED_FIXTURE_MATRIX_TESTS as $relPath) {
            $path = base_path($relPath);
            $this->assertFileExists(
                $path,
                "Plan B.4 second-bullet: expected matrix test at {$relPath} but file is missing"
            );

            $source = (string) file_get_contents($path);
            if (! str_contains($source, 'ColorPaletteSkinMatrixFactory::makeAll(')) {
                $missingConsumers[] = $relPath;
            }
        }

        $this->assertSame(
            [],
            $missingConsumers,
            'Plan B.4 second-bullet: every matrix test must iterate the same fixture set '
            . 'sourced from ColorPaletteSkinMatrixFactory::makeAll() so positive-paint and '
            . 'leak-proof coverage stay aligned. The following tests no longer call '
            . 'makeAll() — refactor them back to the shared factory or update this contract: '
            . json_encode($missingConsumers, JSON_UNESCAPED_SLASHES)
        );
    }

    #[Test]
    public function no_leak_test_iterates_at_least_every_target_skin_with_a_blade(): void
    {
        // The leak-proof matrix derives its iteration set from
        // ColorPaletteSkinMatrixFactory::makeAll(), which is filtered
        // to availableSkins() — every TARGET_SKINS entry whose blade
        // is on disk. This sanity check pins the size relationship:
        // availableSkins() should be a subset of TARGET_SKINS, and
        // the positive set should not be empty (which would silently
        // mean the no-leak test runs zero iterations).
        $available = ColorPaletteSkinMatrixFactory::availableSkins();
        $target = ColorPaletteSkinMatrixFactory::TARGET_SKINS;

        $this->assertNotEmpty(
            $available,
            'Plan B.4 second-bullet: ColorPaletteSkinMatrixFactory::availableSkins() returned '
            . 'an empty list, so both matrix tests would iterate nothing. Either no skin blade '
            . 'is shipped (highly unlikely) or the factory is broken.'
        );

        foreach ($available as $skin) {
            $this->assertContains(
                $skin,
                $target,
                "Plan B.4 second-bullet: availableSkins() returned '{$skin}' but it is not in "
                . 'TARGET_SKINS. The factory must derive available skins by filtering '
                . 'TARGET_SKINS, not by scanning the templates directory directly — otherwise '
                . 'a stray blade file would silently widen matrix coverage past the curated set.'
            );
        }
    }
}
