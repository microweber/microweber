<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Browser smoke for the Phase-11 empty-state CTA widget.
 *
 * A dev install always has content, so the widget's visibility gate
 * is `false`. The test verifies:
 *   - the dashboard still renders cleanly (no 500, no Whoops) with
 *     the widget registered,
 *   - the CTA tile is NOT visible while content exists (guarantees
 *     we aren't accidentally nagging operators with an empty
 *     install's upsell once they have live rows).
 */
class LiveAdminWordPressImportCtaWidgetTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Rely on an already-running dev server.
    }

    #[Test]
    public function dashboard_renders_cleanly_and_hides_the_cta_when_content_exists(): void
    {
        // The live dev DB has content — assert that before driving
        // the browser so a failure here is a loud setup issue, not a
        // misleading UI assertion failure.
        $hasContent = DB::table('content')->limit(1)->count() > 0;
        $this->assertTrue(
            $hasContent,
            'This smoke test assumes the dev DB has at least one content row — run the installer first if not.'
        );

        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin')->pause(3000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Dashboard should not 500 with the WordPress CTA widget registered');
            $this->assertStringNotContainsString('Whoops', $pageSource,
                'Dashboard should render cleanly with the WordPress CTA widget registered');

            // CTA must NOT be on screen while live content exists.
            $ctaPresent = $browser->script(
                'return document.querySelector("[data-testid=\'wp-import-cta\']") !== null;'
            )[0] ?? false;
            $this->assertFalse($ctaPresent,
                'CTA tile must be hidden when the content table is non-empty');
        });
    }
}
