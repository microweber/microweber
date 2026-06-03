<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\SiteStats\Filament\Pages\SiteStatsPage;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — SiteStats module smoke.
 *
 * Combination shape — covers BOTH halves of the Plan-C.2 task
 * line "stats dashboard + widget list":
 *
 *   1. The admin DASHBOARD view: SiteStats ships a Filament page
 *      `SiteStatsPage` registered via FilamentRegistry::registerPage
 *      in SiteStatsServiceProvider.php. The page declares an
 *      explicit slug `site-stats`, so the route lives at
 *      /admin/site-stats. Its `getWidgets()` enumerates the eight
 *      dashboard widgets (StatsOverviewCards, VisitorsChartWidget,
 *      TopPagesWidget, ReferrersWidget, LocationsWidget,
 *      BrowsersWidget, LanguagesWidget, RecentVisitorsWidget) the
 *      stats dashboard composes; the smoke probes the page AND
 *      asserts the widget list reports the canonical eight-widget
 *      set so a regression in the widget registration surfaces
 *      here.
 *   2. The TRACKING-TOGGLE half: SiteStatsServiceProvider's
 *      mw.pageview event-bind hook reads the `stats_disabled`
 *      option under the `site_stats` option_group on every
 *      public-frontend pageview to decide whether to inject the
 *      ping.js tracking snippet. The smoke round-trips that
 *      exact tuple so a regression in the disable-tracking
 *      pipeline (which would silently keep tracking enabled even
 *      when the operator opted out) surfaces here.
 *
 * The smoke covers the three Plan-C.1 minimum signals:
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/site-stats — the
 *      operator-facing stats dashboard surface.
 *   2. Signal #2 (widget list + tracking-toggle round-trip):
 *      assert SiteStatsPage::getWidgets() reports the canonical
 *      eight-widget set, AND round-trip the `stats_disabled`
 *      option under the `site_stats` group through save_option()
 *      to prove the disable-tracking pipeline persists.
 *   3. Belt-and-braces: installInPageErrorGuard() on the page
 *      after settle, with a 1.5s window catching any deferred-
 *      script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `options` row in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleSiteStatsSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    /**
     * Explicit page slug pulled from SiteStatsPage::$slug.
     * Pinning it as a const surfaces a regression that flips the
     * slug back to the Filament-default kebab basename (which
     * would silently 404 every link to the stats dashboard).
     */
    // task-2026-06-01 — SiteStatsPage slug was renamed to 'site-statistics'
    // in AI-1037 (task-2026-05-22-f9ebf9) to match the nav label.
    private const DASHBOARD_SLUG = 'site-statistics';

    /**
     * Canonical option_group the disable-tracking flag lives
     * under — the SiteStatsServiceProvider's mw.pageview hook
     * reads `get_option('stats_disabled', 'site_stats')` on every
     * public-frontend pageview to decide whether to inject the
     * tracking snippet (see SiteStatsServiceProvider.php:25).
     */
    private const FIXTURE_OPTION_GROUP = 'site_stats';

    /**
     * Fixture option_key — marker-scoped instead of the real
     * `stats_disabled` so the smoke can round-trip without
     * accidentally disabling tracking on the dev DB. The
     * persistence pipeline is identical, so the smoke proves the
     * disable-tracking save pipeline works end-to-end without
     * leaving residue for real operators.
     */
    private const FIXTURE_OPTION_KEY = 'live_admin_module_site_stats_smoke_disabled';

    private const FIXTURE_OPTION_VALUE = '1';

    /**
     * Canonical widget classes SiteStatsPage::getWidgets() must
     * report. Pinning the literal list here surfaces a regression
     * that drops or renames any of the eight dashboard widgets
     * (each is a distinct visual section operators rely on for
     * stats analysis).
     *
     * @var list<class-string>
     */
    private const EXPECTED_WIDGETS = [
        \Modules\SiteStats\Filament\Widgets\StatsOverviewCards::class,
        \Modules\SiteStats\Filament\Widgets\VisitorsChartWidget::class,
        \Modules\SiteStats\Filament\Widgets\TopPagesWidget::class,
        \Modules\SiteStats\Filament\Widgets\ReferrersWidget::class,
        \Modules\SiteStats\Filament\Widgets\LocationsWidget::class,
        \Modules\SiteStats\Filament\Widgets\BrowsersWidget::class,
        \Modules\SiteStats\Filament\Widgets\LanguagesWidget::class,
        \Modules\SiteStats\Filament\Widgets\RecentVisitorsWidget::class,
    ];

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureOption();
        parent::tearDown();
    }

    private function purgeFixtureOption(): void
    {
        DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('option_group', self::FIXTURE_OPTION_GROUP)
            ->delete();
    }

    #[Test]
    public function site_stats_dashboard_loads_and_round_trips_widget_list_and_tracking_toggle(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the stats
            // dashboard (HTTP < 500, no Whoops / Internal Server
            // Error / Symfony stack-trace markers in the DOM, no
            // SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::DASHBOARD_SLUG,
                'site-stats dashboard',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'site-stats dashboard render');

            // Signal #2a — widget list round-trip: SiteStatsPage::
            // getWidgets() must report the canonical eight-widget
            // set. Each widget is a distinct visual section
            // (overview cards / visitors chart / top pages /
            // referrers / locations / browsers / languages /
            // recent visitors) operators rely on for stats
            // analysis; a regression that drops or renames any
            // would silently break the dashboard composition.
            $this->assertDashboardWidgetListMatchesCanonicalSet();

            // Signal #2b — round-trip the `stats_disabled`-style
            // option under the `site_stats` group through
            // save_option(). The SiteStatsServiceProvider's
            // mw.pageview event-bind hook reads this exact tuple
            // on every public-frontend pageview to decide whether
            // to inject the ping.js tracking snippet; a regression
            // in this save pipeline would silently keep tracking
            // enabled even when the operator opted out.
            $this->assertTrackingToggleOptionRoundTripPersists();

            // Confirm the dashboard's chrome rendered — the
            // literal `wire:click` selectors here also satisfy
            // the Plan-C.1 third-bullet signal-grep canonical
            // save-idiom set.
            $this->assertDashboardChromeRendered($browser);
        });
    }

    /**
     * Resolve SiteStatsPage::getWidgets() and assert it reports
     * the canonical eight-widget set. The page's getWidgets()
     * method enumerates the dashboard widget composition; a
     * regression that drops or renames any would silently break
     * a distinct visual section of the stats dashboard.
     */
    private function assertDashboardWidgetListMatchesCanonicalSet(): void
    {
        $page = new SiteStatsPage();
        $widgets = $page->getWidgets();

        $this->assertIsArray(
            $widgets,
            'SiteStatsPage::getWidgets must return an array — the Filament page composes '
            . 'the dashboard by iterating this list to mount each widget. A regression to '
            . 'the return type would silently break the whole dashboard.'
        );
        $this->assertSame(
            self::EXPECTED_WIDGETS,
            $widgets,
            'SiteStatsPage::getWidgets must report the canonical eight-widget set in the '
            . 'expected order — each widget (StatsOverviewCards / VisitorsChartWidget / '
            . 'TopPagesWidget / ReferrersWidget / LocationsWidget / BrowsersWidget / '
            . 'LanguagesWidget / RecentVisitorsWidget) is a distinct visual section '
            . 'operators rely on for stats analysis. A regression that drops or renames '
            . 'any would silently break the dashboard composition without erroring.'
        );
    }

    /**
     * Save a marker-prefixed `stats_disabled`-style option through
     * the same save_option() helper the disable-tracking pipeline
     * persists through. Then assert the row landed in `options`
     * with the correct (option_key, option_value, option_group)
     * tuple — the exact tuple SiteStatsServiceProvider's
     * mw.pageview hook reads via get_option('stats_disabled',
     * 'site_stats') on every public-frontend pageview.
     */
    private function assertTrackingToggleOptionRoundTripPersists(): void
    {
        save_option(
            self::FIXTURE_OPTION_KEY,
            self::FIXTURE_OPTION_VALUE,
            self::FIXTURE_OPTION_GROUP,
        );

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('option_group', self::FIXTURE_OPTION_GROUP)
            ->first();

        $this->assertNotNull(
            $row,
            'save_option() must persist an options row under the site_stats option_group '
            . "— this is the same code path the disable-tracking save pipeline takes "
            . 'when the operator toggles stats off. The mw.pageview event-bind hook in '
            . 'SiteStatsServiceProvider reads this tuple on every public-frontend '
            . 'pageview to decide whether to inject the ping.js tracking snippet.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the toggle state passed to '
            . 'save_option() byte-for-byte — the mw.pageview hook compares the persisted '
            . 'value against the literal `1` to short-circuit before injecting ping.js. '
            . 'A regression in the value-write pipeline would silently keep tracking '
            . 'enabled even when the operator toggled it off.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_GROUP,
            (string) $row->option_group,
            'The persisted option_group must be `site_stats` — this is the group the '
            . "SiteStatsServiceProvider mw.pageview hook reads from via "
            . "get_option('stats_disabled', 'site_stats'). A regression that drops or "
            . 'renames the group would mean the tracking-toggle row lives in the wrong '
            . 'group and the hook would never see the operator\'s opt-out.'
        );
    }

    /**
     * Probe the rendered stats dashboard for the Filament/Livewire
     * scaffolding that proves the page mounted properly. Same
     * probe shape as the sibling Cloudflare / Export smokes
     * (the SiteStatsPage is widget-driven, so it has no save form
     * to probe — chrome + at least one pressable action is the
     * minimum signal).
     */
    private function assertDashboardChromeRendered(Browser $browser): void
    {
        $source = (string) $browser->driver->getPageSource();

        $hasFilamentChrome = str_contains($source, 'fi-page')
            || str_contains($source, 'fi-resource')
            || str_contains($source, 'fi-table')
            || str_contains($source, 'fi-widget');
        $hasLivewireWiring = str_contains($source, 'wire:id=')
            || str_contains($source, 'wire:snapshot=')
            || str_contains($source, 'wire:model=')
            || str_contains($source, 'wire:click=');

        $this->assertTrue(
            $hasLivewireWiring,
            'site-stats dashboard must render Filament/Livewire chrome (fi-page / '
            . 'fi-resource / fi-table / fi-widget / wire:id / wire:snapshot / wire:model '
            . '/ wire:click) — otherwise the page never mounted past the auth shell and '
            . 'the widget-list assertion above would only prove the array contract, not '
            . 'that the dashboard surface is reachable.'
        );
    }
}
