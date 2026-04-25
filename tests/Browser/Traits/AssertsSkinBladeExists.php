<?php

declare(strict_types=1);

namespace Tests\Browser\Traits;

/**
 * Plan B.3 first-bullet helper — every per-skin test must fail
 * early (with a useful message) when the skin's blade file is
 * missing on disk, instead of letting the failure surface as a
 * cryptic "section never appeared in the canvas within 15s" lower
 * down the test body.
 *
 * The newer per-skin tests (Features/Pricing/TextBlock/Menus
 * skin-1, Jumbotron skin-2) already inline an `assertFileExists`
 * call. This trait factors the path-derivation rule out of those
 * call sites so older sibling tests can opt into the same gate
 * with one method call instead of duplicating the
 * `Templates/Bootstrap/resources/views/modules/layouts/templates/<family>/skin-<N>.blade.php`
 * literal.
 *
 * Usage in a per-skin test:
 *
 *   use Tests\Browser\Traits\AssertsSkinBladeExists;
 *
 *   class LiveEditXxxSkinNTest extends DuskTestCase
 *   {
 *       use AssertsSkinBladeExists;
 *
 *       public function xxx_skin_n_inserts_…(): void
 *       {
 *           $this->assertSkinBladeExists('xxx/skin-N');
 *           // … rest of the test …
 *       }
 *   }
 *
 * The composing class must extend a PHPUnit\Framework\TestCase
 * (so {@see assertFileExists()} is available); every Dusk test
 * already inherits that contract.
 */
trait AssertsSkinBladeExists
{
    /**
     * Assert the Bootstrap blade file backing a skin tag like
     * `pricing/skin-2` exists on disk. The error message names
     * the family + skin so the failure surfaces as
     * "pricing/skin-2 blade missing — author the skin or remove
     * the test" rather than "file not found".
     *
     * Bootstrap is the only template that ships these layout
     * skins (Plan B's whole skin matrix is scoped to it), so the
     * helper hard-codes the Bootstrap path.
     */
    protected function assertSkinBladeExists(string $skinTag): void
    {
        $relativePath = sprintf(
            'Templates/Bootstrap/resources/views/modules/layouts/templates/%s.blade.php',
            $skinTag,
        );

        $this->assertFileExists(
            base_path($relativePath),
            sprintf(
                '%s blade file (%s) must exist on disk before this test '
                . 'can drive the live-edit pipeline. Either ship the skin '
                . "or remove '%s' from the per-skin test inventory — the "
                . 'state here means the per-skin test is asserting against '
                . 'a skin the operator can never insert.',
                $skinTag,
                $relativePath,
                $skinTag,
            ),
        );
    }
}
