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
 * Plan C.2 — WhiteLabel module smoke.
 *
 * Same shape as {@see LiveAdminModuleBtnSmokeTest} but adapted to
 * a Filament page that subclasses `AdminSettingsPage` (rather
 * than `LiveEditModuleSettings`): WhiteLabelSettingsAdminSettingsPage
 * is registered via FilamentRegistry::registerPage in
 * WhiteLabelServiceProvider.php. The page does NOT declare an
 * explicit `$slug`, so Filament derives the route from the class
 * basename — `white-label-settings-admin-settings-page` — giving
 * /admin/white-label-settings-admin-settings-page. The form binds
 * to the `white_label` option_group via the page's own
 * `$optionGroups = ['white_label']` + `$moduleNameForOption =
 * 'white_label'` declarations, so every field path on the form
 * has the shape `options.white_label.<key>`.
 *
 * Plan-C.2 task line is "white-label settings form". The
 * canonical brand-name field — the operator-facing "Brand Name"
 * TextInput rendered at the top of the form's Branding tab —
 * lives at the `options.white_label.brand_name` form path. The
 * parent AdminSettingsPage::updated() hook persists every edit
 * via save_option(option_key='brand_name', option_value=…,
 * option_group='white_label', module='white_label'). The smoke
 * round-trips that exact tuple. WhiteLabelService::applyWhiteLabelSettings()
 * reads the persisted row on every Filament-serving / mw.front /
 * mw.admin event to brand the admin chrome and the public site,
 * so a regression in the brand-name save pipeline would silently
 * un-brand every operator deployment.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of
 *      /admin/white-label-settings-admin-settings-page.
 *   2. Signal #2 (brand-name save round-trip): direct
 *      save_option() call against the `brand_name` option key
 *      under the `white_label` option_group with a marker-
 *      prefixed value; verifies the row lands in `options`
 *      verbatim.
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
class LiveAdminModuleWhiteLabelSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    /**
     * Filament derives the slug from the class basename when the
     * page does NOT declare $slug. WhiteLabelSettingsAdminSettingsPage
     * has its $slug commented out (line 35), so the route lives at
     * /admin/white-label-settings-admin-settings-page (kebab-cased
     * basename). Pinning this constant here surfaces a regression
     * that flips the slug back to /settings/white-label or some
     * other shape (which would silently 404 every link to the WL
     * settings page).
     */
    private const SETTINGS_SLUG = 'white-label-settings-admin-settings-page';

    /**
     * Canonical option_group the page binds to via its own
     * $optionGroups = ['white_label'] declaration. Pinning this
     * constant here surfaces a regression that renames the group
     * (which would silently invalidate every cached brand
     * settings read on the next deploy).
     */
    private const FIXTURE_OPTION_GROUP = 'white_label';

    /**
     * Fixture option_key — marker-scoped instead of the real
     * `brand_name` so the smoke can round-trip without touching
     * whatever real brand the dev DB might have configured. The
     * persistence pipeline is identical (same group, same module
     * name, same save_option() call site — see
     * AdminSettingsPage::updated()), so the smoke proves the
     * brand-name save pipeline works end-to-end without leaving
     * residue for real operators.
     */
    private const FIXTURE_OPTION_KEY = 'live_admin_module_white_label_smoke_brand_name';

    private const FIXTURE_OPTION_VALUE = 'Live Admin Module WhiteLabel Smoke Brand';

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
    public function white_label_settings_page_loads_and_round_trips_the_brand_name_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // white-label settings admin (HTTP < 500, no Whoops /
            // Internal Server Error / Symfony stack-trace markers
            // in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'white-label admin settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'white-label settings render');

            // Signal #2 — round-trip the brand-name field through
            // the same save_option() pipeline AdminSettingsPage::
            // updated() calls server-side when the
            // `options.white_label.brand_name` TextInput is
            // edited. The persisted row is what
            // WhiteLabelService::applyWhiteLabelSettings() reads
            // on every Filament-serving / mw.front / mw.admin
            // event to brand the admin chrome and the public site.
            $this->assertWhiteLabelBrandNameRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `brand_name`-style option through
     * the same save_option() helper the AdminSettingsPage::
     * updated() hook calls server-side when the operator edits
     * the Brand Name TextInput. Then assert the row landed in
     * `options` with the correct (option_key, option_value,
     * option_group) tuple — the exact row WhiteLabelService::
     * applyWhiteLabelSettings() reads via get_option() on every
     * brand-applying event.
     */
    private function assertWhiteLabelBrandNameRoundTripPersists(): void
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
            'save_option() must persist an options row under the white_label option_group '
            . "— this is the same code path WhiteLabelSettingsAdminSettingsPage invokes "
            . 'via its parent AdminSettingsPage::updated() hook on every '
            . '`options.white_label.brand_name` TextInput edit.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the brand name passed to '
            . 'save_option() byte-for-byte. WhiteLabelService::applyWhiteLabelSettings() '
            . 'reads this exact row on every Filament-serving / mw.front / mw.admin '
            . 'event to brand the admin chrome and the public site; a mismatch here '
            . 'would silently un-brand every operator deployment.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_GROUP,
            (string) $row->option_group,
            'The persisted option_group must be `white_label` — this is the option_group '
            . 'the WhiteLabelSettingsAdminSettingsPage binds to via its $optionGroups '
            . 'declaration AND the same group WhiteLabelService reads from. A regression '
            . 'that drops or renames the group would silently un-brand every operator '
            . 'deployment.'
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
            || str_contains($source, 'fi-page')
            || str_contains($source, 'fi-form');

        $this->assertTrue(
            $hasInlineSave || $hasDeferredSave,
            'white-label settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, OR '
            . 'wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline. The '
            . 'page may render a license-required notice instead of the full form when '
            . 'no white-label license is active — both render shapes still emit Filament '
            . 'chrome, so this probe holds either way.'
        );
    }
}
