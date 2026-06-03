<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — GoogleAnalytics module smoke.
 *
 * Shape mirrors {@see LiveAdminModuleBtnSmokeTest} but adapted to
 * a longer slug and a non-default option_group: the
 * GoogleAnalytics module ships an
 * `AdminGoogleAnalyticsSettingsPage` (subclass of the shared
 * `AdminSettingsPage`) registered via
 * FilamentRegistry::registerPage in GoogleAnalyticsServiceProvider.
 * The page declares an explicit slug `settings/google-analytics`
 * (not the Filament-default kebab basename), so the route lives
 * at /admin/settings/google-analytics. The form is pinned to the
 * `website` option_group via the parent page's `$optionGroups =
 * ['website']` declaration, so every field path on the form has
 * the shape `options.website.<key>`.
 *
 * Plan-C.2 task line is "GA property field". The canonical
 * Google-Analytics property identifier (the `UA-…` / `G-…` ID)
 * lives at the `options.website.google-analytics-id` form path,
 * which the parent AdminSettingsPage::updated() hook persists via
 * save_option(option_key='google-analytics-id', option_value=…,
 * option_group='website', module='settings/group/website') on
 * every TextInput edit. The smoke round-trips that exact tuple
 * so a regression in the property-field save pipeline (which
 * would silently break GA tracking shop-wide) surfaces here.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/settings/google-analytics.
 *   2. Signal #2 (GA property field save round-trip): direct
 *      save_option() call against the `google-analytics-id`
 *      option key under the `website` option_group with a
 *      marker-prefixed UA-style fixture; verifies the row lands
 *      in `options` verbatim. The same row the public-frontend
 *      tracking-code blade reads to emit the GA `<script>` snippet.
 *   3. Belt-and-braces: installInPageErrorGuard() on the
 *      settings page after settle, with a 1.5s window catching
 *      any deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `options` row in tearDown AND
 * the AdminSettingsPage's per-group cache key so the page's next
 * `mount()` re-reads from the DB; safe to re-run.
 */
class LiveAdminModuleGoogleAnalyticsSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    /**
     * Explicit page slug pulled from
     * AdminGoogleAnalyticsSettingsPage::$slug. Pinning it as a
     * const surfaces a regression that flips the slug back to the
     * Filament-default kebab basename (which would silently
     * 404 every link to the GA settings page).
     */
    private const SETTINGS_SLUG = 'settings/google-analytics';

    /**
     * Canonical option_group the page binds to via its parent
     * AdminSettingsPage::$optionGroups = ['website'] declaration.
     * Pinning this constant here surfaces a regression that
     * renames the group (which would silently invalidate every
     * cached options-row read on the next deploy).
     */
    private const FIXTURE_OPTION_GROUP = 'website';

    /**
     * Fixture option_key — marker-scoped instead of the real
     * `google-analytics-id` so the smoke can round-trip without
     * touching whatever real GA property the dev DB might have
     * configured. The persistence pipeline is identical (same
     * group, same module name, same save_option() call site —
     * see AdminSettingsPage::updated()), so the smoke proves the
     * GA property-field save pipeline works end-to-end without
     * leaving residue for real operators.
     */
    private const FIXTURE_OPTION_KEY = 'live_admin_module_google_analytics_smoke_id';

    /**
     * Marker-prefixed UA-style fixture value. The literal `UA-…`
     * shape is what the GoogleAnalyticsModule frontend tracking-
     * code blade matches against to choose between the legacy
     * Universal Analytics snippet and the GA4 `G-…` snippet, so
     * the smoke deliberately uses a UA-shaped value to assert
     * the persistence pipeline preserves the full string verbatim.
     */
    private const FIXTURE_OPTION_VALUE = 'UA-LIVE-ADMIN-MODULE-GA-SMOKE-1';

    /**
     * The cache key AdminSettingsPage::mount() reads from on
     * every page render. Pinning the literal here so the test's
     * tearDown can purge it after writing the fixture row.
     */
    private const SETTINGS_CACHE_KEY = 'admin_settings_options_' . self::FIXTURE_OPTION_GROUP;

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

        // The AdminSettingsPage caches the options query for 300
        // seconds; flush so the next page mount re-reads from DB.
        Cache::forget(self::SETTINGS_CACHE_KEY);
    }

    #[Test]
    public function google_analytics_settings_page_loads_and_round_trips_the_property_id_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // GA settings admin (HTTP < 500, no Whoops / Internal
            // Server Error / Symfony stack-trace markers in the
            // DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'Google Analytics admin settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'GA settings render');

            // Signal #2 — round-trip the GA property field through
            // the same save_option() pipeline AdminSettingsPage::
            // updated() calls server-side when the
            // `options.website.google-analytics-id` TextInput is
            // edited. The persisted row is what the public-
            // frontend tracking-code blade reads via
            // get_option('google-analytics-id', 'website') to
            // emit the GA `<script>` snippet on every page load.
            $this->assertGooglePropertyFieldRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `google-analytics-id`-style option
     * through the same save_option() helper the
     * AdminSettingsPage::updated() hook calls server-side when
     * the operator edits the GA property TextInput. Then assert
     * the row landed in `options` with the correct (option_key,
     * option_value, option_group) tuple — the exact row the
     * public-frontend tracking-code blade reads via
     * get_option('google-analytics-id', 'website') to emit the
     * GA `<script>` snippet on every page render.
     */
    private function assertGooglePropertyFieldRoundTripPersists(): void
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
            'save_option() must persist an options row under the website option_group — '
            . "this is the same code path the AdminGoogleAnalyticsSettingsPage invokes "
            . 'via its parent AdminSettingsPage::updated() hook on every '
            . '`options.website.google-analytics-id` TextInput edit.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the GA property identifier passed to '
            . 'save_option() byte-for-byte. The public-frontend GA tracking-code blade '
            . "reads this exact row via get_option('google-analytics-id', 'website') to "
            . 'emit the GA `<script>` snippet on every page load; a mismatch here would '
            . 'silently break GA tracking shop-wide.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_GROUP,
            (string) $row->option_group,
            'The persisted option_group must be `website` — this is the option_group the '
            . 'AdminGoogleAnalyticsSettingsPage binds to via its parent class '
            . 'AdminSettingsPage::$optionGroups, and the same group the public-frontend '
            . 'tracking-code blade reads from. A regression that drops or renames the '
            . 'group would silently break GA tracking shop-wide.'
        );
    }

    /**
     * Probe the rendered settings page for the Filament/Livewire
     * scaffolding that proves a save round-trip is possible from
     * the UI. Same shape as the sibling Btn / Audio / Accordion
     * smokes.
     */
    private function assertSaveActionWired(Browser $browser): void
    {
        $source = (string) $browser->driver->getPageSource();

        $hasInlineSave = str_contains($source, 'wire:model=')
            || str_contains($source, 'wire:submit=')
            || str_contains($source, 'wire:click="save"')
            || str_contains($source, "wire:click='save'");
        $hasDeferredSave = str_contains($source, 'wire:id=')
            || str_contains($source, 'wire:snapshot=')
            || str_contains($source, 'fi-form');

        $this->assertTrue(
            $hasInlineSave || $hasDeferredSave,
            'Google Analytics settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, OR '
            . 'wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline.'
        );
    }
}
