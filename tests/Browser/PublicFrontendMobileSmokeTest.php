<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AssertsMobileViewport;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Mobile-viewport regression guard for the public frontend.
 *
 * The earlier task-2026-04-26-5ca263 / task-2026-04-26-2e2541 /
 * task-2026-04-26-dcd55a / task-2026-04-27-a9cfee / task-2026-04-27-a69f1f
 * fix-batches all addressed real mobile bugs that operators
 * reported by hand:
 *   - empty-state heading clipped on /admin/pages,
 *   - product table overflowing by 32px,
 *   - search input squashed to "Sea…" placeholder,
 *   - logo pushing the hamburger off-screen on narrow phones,
 *   - two hamburger icons rendering side-by-side,
 *   - module skins shipping `<img>` tags without `img-fluid`,
 *   - inline-table elements without `.table-responsive` wrapper,
 *   - the singular `picture` element overflowing by 207px.
 *
 * Each fix shipped under a different commit. This test file is
 * the regression catcher — it exercises the public homepage AND
 * a real built page (/component-audit, the static fixture page
 * that drops btn / title / text / picture / contact_form /
 * search / breadcrumb / newsletter into three column widths) at
 * the same 390×844 viewport that the hand-reported regressions
 * surfaced under, and asserts:
 *
 *   1. body.scrollWidth ≤ window.innerWidth — no element
 *      overflows the viewport horizontally.
 *   2. The public template's mobile hamburger renders exactly
 *      once (no static HTML duplicate competing with the JS
 *      injection).
 *
 * If a future contributor introduces a regression that fits any
 * of the historical patterns above — a new <img> without
 * img-fluid, a new component with fixed width, a static
 * hamburger reintroduced into a skin — this test fails before
 * the regression ships.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000.
 */
class PublicFrontendMobileSmokeTest extends DuskTestCase
{
    use AssertsMobileViewport;
    use AssertsSkinConsoleClean;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    #[Test]
    public function homepage_renders_without_horizontal_overflow_on_mobile(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $this->withMobileViewport($browser, function (Browser $browser): void {
                $browser->pause(500); // settle layout reflow after resize
                $this->assertNoHorizontalOverflowOnMobile($browser, 'public homepage');
                $this->assertHamburgerRenderedOnce($browser, 'public homepage');
            });
        });
    }

    #[Test]
    public function component_audit_page_renders_without_horizontal_overflow_on_mobile(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/component-audit');

            $this->withMobileViewport($browser, function (Browser $browser): void {
                $browser->pause(500);
                $this->assertNoHorizontalOverflowOnMobile(
                    $browser,
                    'component-audit fixture page'
                );
                $this->assertHamburgerRenderedOnce(
                    $browser,
                    'component-audit fixture page'
                );
            });
        });
    }
}
