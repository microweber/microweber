<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\ColorPaletteSkinMatrixFactory;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Plan B.2 — Jumbotron / skin-2 per-skin Dusk coverage.
 *
 * Mirror of {@see LiveEditJumbotronSkin1Test} for the sibling
 * `jumbotron/skin-2` skin. The two tests together prove both
 * Jumbotron skins survive the Phase-2 live-edit pipeline.
 *
 * Pending-skin contract:
 *   `jumbotron/skin-2` is listed in
 *   {@see ColorPaletteSkinMatrixFactory::TARGET_SKINS} but its
 *   blade file is not on disk yet — see `pendingSkins()`. The
 *   full insert-edit-persist flow depends on the resolver
 *   rendering the skin, so this test marks itself skipped
 *   with a loud, specific message when the blade is missing.
 *
 * When the blade lands
 * (Templates/Bootstrap/resources/views/modules/layouts/templates/jumbotron/skin-2.blade.php),
 * the author who ships it should:
 *   1. Extract LiveEditJumbotronSkin1Test's private helpers
 *      (primeLayoutHandleOnMainContent, editJumbotronH1,
 *      editJumbotronCtaText, markEditFieldsChanged,
 *      assertCanvasReflectsEdits, assertSavedContentBodyContains,
 *      waitForJumbotronSection) into a shared
 *      LiveEditJumbotronTrait.
 *   2. Compose the trait on both skin tests and parametrise the
 *      `jumbotron/skin-{N}` strings through a SKIN_TAG constant.
 *   3. Replace this test's skip with the full insert → edit →
 *      save → canvas + DB assertion cycle that Skin 1 covers.
 *
 * Plan B.3 contract (assertions the full-flow body must cover
 * once the blade lands):
 *   - Skin blade file exists before attempting insert (already
 *     checked here via {@see skinBladeExists}).
 *   - Insert writes `<module type="layouts" template="jumbotron/skin-2">`
 *     to content.content.
 *   - Public render carries `field="layout-jumbotron-skin-2-*"`
 *     on the outer <section>, mirroring Skin 1's field probe.
 *   - No JS console error fires during insert or public render.
 *
 * Until the blade ships, this test also exercises the
 * pending-skin contract: the factory's pendingSkins() output
 * and the file-existence probe agree. A regression that drops
 * skin-2 from TARGET_SKINS without landing the blade would
 * surface as the `pendingSkins()` assertion failing here.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditJumbotronSkin2Test extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    private const SKIN_TAG = 'jumbotron/skin-2';
    private const SKIN_BLADE_PATH = 'Templates/Bootstrap/resources/views/modules/layouts/templates/jumbotron/skin-2.blade.php';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    #[Test]
    public function jumbotron_skin_2_inserts_edits_and_persists(): void
    {
        // Plan B.3 first-bullet gate: the blade must exist before
        // any insert path makes sense. While it doesn't, we also
        // assert the factory agrees the skin is a known pending
        // target — this is the regression guard that a contributor
        // dropping skin-2 from TARGET_SKINS without landing the
        // blade cannot silently hide.
        if (! $this->skinBladeExists()) {
            $this->assertContains(
                self::SKIN_TAG,
                ColorPaletteSkinMatrixFactory::pendingSkins(),
                sprintf(
                    'jumbotron/skin-2 has no blade on disk AND is not listed in '
                    . 'ColorPaletteSkinMatrixFactory::pendingSkins(). Either ship %s '
                    . "or remove '%s' from TARGET_SKINS — the state here means the "
                    . 'palette-matrix expectations are drifting from reality.',
                    self::SKIN_BLADE_PATH,
                    self::SKIN_TAG,
                ),
            );

            $this->markTestSkipped(sprintf(
                "jumbotron/skin-2 blade is not yet shipped (a known pending target). "
                . "Drop %s and extract LiveEditJumbotronSkin1Test's private helpers "
                . "into a shared trait so this test can exercise the full "
                . 'insert → edit → save → render cycle.',
                self::SKIN_BLADE_PATH,
            ));
        }

        // When the blade lands, the body below should mirror
        // LiveEditJumbotronSkin1Test::jumbotron_skin_1_inserts_edits_and_persists.
        // The fail-until-filled-in assertion below is intentional —
        // it reminds the blade author that this test still has a
        // TODO body to fill out.
        $this->fail(sprintf(
            'jumbotron/skin-2 blade now exists at %s — fill in the full '
            . "insert → edit → save → render assertions here. See "
            . 'LiveEditJumbotronSkin1Test::jumbotron_skin_1_inserts_edits_and_persists '
            . 'for the reference shape; extract its private helpers into a shared '
            . "trait composed on both skin tests so skin-2 doesn't duplicate them.",
            self::SKIN_BLADE_PATH,
        ));
    }

    /**
     * Plan B.3 first-bullet helper — does the skin's blade exist on
     * disk right now? This is the skip/proceed gate above.
     */
    private function skinBladeExists(): bool
    {
        return is_file(base_path(self::SKIN_BLADE_PATH));
    }
}
