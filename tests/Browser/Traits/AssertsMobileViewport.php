<?php

namespace Tests\Browser\Traits;

use Facebook\WebDriver\WebDriverDimension;
use Laravel\Dusk\Browser;

/**
 * Mobile-viewport helpers for module smoke tests.
 *
 * The default DuskTestCase boots Chrome at 1280×1080. This trait
 * lets a smoke flip the test browser to a phone viewport (390×844,
 * matching iPhone 12/13/14) for the duration of a single
 * assertion, run the public-facing or admin page through that
 * viewport, and assert two invariants that catch the most common
 * mobile regressions:
 *
 *   1. **No horizontal overflow** — `body.scrollWidth` stays
 *      within the viewport's inner width (±1px tolerance for
 *      sub-pixel rounding). A site that scrolls horizontally on a
 *      390px viewport is broken for ~50% of real-world traffic.
 *
 *   2. **Hamburger menu rendered exactly once** — the public
 *      template's mobile menu icon must be a single instance.
 *      Two hamburger icons stacked (the regression that
 *      task-2026-04-26-dcd55a fixed) is a real-world bug class
 *      we want to keep regressing-against.
 *
 * Usage:
 *
 *     $browser->visit('/');
 *     $this->withMobileViewport($browser, function (Browser $browser): void {
 *         $this->assertNoHorizontalOverflowOnMobile($browser, 'home');
 *         $this->assertHamburgerRenderedOnce($browser, 'home');
 *     });
 *
 * The trait restores the desktop viewport after the callback so
 * subsequent assertions in the same test run unaffected.
 */
trait AssertsMobileViewport
{
    /**
     * Default phone viewport — iPhone 12/13/14 in portrait. The
     * 390×844 dimensions are what Playwright's default-mobile
     * preset uses, so failing assertions here translate cleanly
     * to follow-up Playwright debug sessions.
     */
    protected int $mobileViewportWidth = 390;

    protected int $mobileViewportHeight = 844;

    /**
     * Default desktop viewport — matches DuskTestCase::driver()'s
     * `--window-size=1280,1080` Chrome arg so we restore to the
     * shape every other test in the file expects.
     */
    protected int $desktopViewportWidth = 1280;

    protected int $desktopViewportHeight = 1080;

    /**
     * Run a callback with the browser resized to the mobile
     * viewport. Restores the desktop viewport afterwards.
     */
    protected function withMobileViewport(Browser $browser, callable $callback): void
    {
        $browser->driver->manage()->window()->setSize(
            new WebDriverDimension($this->mobileViewportWidth, $this->mobileViewportHeight)
        );
        try {
            $callback($browser);
        } finally {
            $browser->driver->manage()->window()->setSize(
                new WebDriverDimension($this->desktopViewportWidth, $this->desktopViewportHeight)
            );
        }
    }

    /**
     * Assert the page does not horizontally overflow on the
     * current (mobile) viewport. `body.scrollWidth` stays within
     * `window.innerWidth + 1`. The +1 tolerates sub-pixel rounding
     * — a real overflow puts scrollWidth ≥ innerWidth + ~16px
     * (typical scrollbar gutter or content overshoot).
     */
    protected function assertNoHorizontalOverflowOnMobile(
        Browser $browser,
        string $context
    ): void {
        $report = $browser->driver->executeScript(<<<'JS'
            return {
                scrollWidth: document.body.scrollWidth,
                innerWidth: window.innerWidth,
                clientWidth: document.documentElement.clientWidth,
            };
        JS);

        $scrollWidth = (int) ($report['scrollWidth'] ?? 0);
        $innerWidth = (int) ($report['innerWidth'] ?? 0);
        $tolerance = 1;

        $this->assertLessThanOrEqual(
            $innerWidth + $tolerance,
            $scrollWidth,
            sprintf(
                'Mobile horizontal overflow on %s: body.scrollWidth=%dpx exceeds '
                . 'window.innerWidth=%dpx (+%dpx tolerance). An element on this page '
                . 'is wider than the viewport, which forces the entire layout to '
                . 'scroll horizontally on every phone visit. Common causes: an '
                . '<img> without img-fluid rendering at intrinsic pixel size, a '
                . '<table> without .table-responsive wrapper, a bare iframe, or a '
                . 'fixed-width inline style. Re-run the failing test with '
                . 'Playwright at 390×844 to identify the offender.',
                $context,
                $scrollWidth,
                $innerWidth,
                $tolerance
            )
        );
    }

    /**
     * Assert the public-template's mobile hamburger renders exactly
     * once. `.mw-vhmbgr-wrapper` is the canonical selector; the
     * MWSiteMobileMenu JS injects one per `.mw-vhmbgr--navigation`
     * it finds. A static HTML duplicate (the regression
     * task-2026-04-26-dcd55a fixed in skin-1.blade.php) would
     * push the count above 1.
     */
    protected function assertHamburgerRenderedOnce(
        Browser $browser,
        string $context
    ): void {
        $count = (int) $browser->driver->executeScript(
            'return document.querySelectorAll(".mw-vhmbgr-wrapper").length;'
        );

        $this->assertSame(
            1,
            $count,
            sprintf(
                'Mobile hamburger menu render count on %s: expected exactly 1 '
                . '.mw-vhmbgr-wrapper element, got %d. More than 1 means a static '
                . 'HTML hamburger is duplicating the JS-injected one (see '
                . 'task-2026-04-26-dcd55a — skin-1.blade.php used to ship a '
                . 'hardcoded SVG that competed with MWSiteMobileMenu); 0 means '
                . 'the JS injection broke entirely.',
                $context,
                $count
            )
        );
    }
}
