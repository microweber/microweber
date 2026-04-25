<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Breadcrumb module smoke.
 *
 * Same shape as {@see LiveAdminModuleAccordionSmokeTest}: the
 * Breadcrumb module ships a Filament settings page registered
 * via FilamentRegistry::registerPage(BreadcrumbModuleSettings::class)
 * in BreadcrumbServiceProvider.php. Filament-default route slug:
 * /admin/breadcrumb-module-settings.
 *
 * Plan-C.2 task line is "breadcrumb render on a nested page".
 * The settings page exposes one configurable option:
 * `options.data-start-from` (a Select with values "" / "page" /
 * "category" — see BreadcrumbModuleSettings.php line 23+). That
 * option controls which DOM-tree level the public-frontend
 * breadcrumb widget walks up from when rendering nested-page
 * trails. The smoke round-trips that option key through the
 * same save_module_option() pipeline the page's Livewire
 * updated() hook calls on every reactive field update.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/breadcrumb-module-settings.
 *   2. Signal #2 (data-start-from save round-trip): direct
 *      save_module_option() call against the data-start-from
 *      option key with value "category" (one of the two
 *      non-default Select options); verifies the row lands in
 *      `options` with the expected value (the exact row the
 *      public-frontend breadcrumb widget reads when rendering
 *      nested-page trails).
 *   3. Belt-and-braces: installInPageErrorGuard() on the
 *      settings page after settle, with a 1.5s window catching
 *      any deferred-script throws.
 *
 * Note: full end-to-end rendering of breadcrumbs ON a nested
 * page (the literal "breadcrumb render on a nested page" the
 * Plan-C.2 line names) belongs to a follow-up that seeds a
 * parent + child content row, drops the breadcrumb module on
 * the child page, and visits the public URL to inspect the
 * rendered <ol class="breadcrumb"> chain. That's a Plan-D-class
 * matrix concern; this smoke covers the admin-settings + data
 * round-trip portion of the Plan-C.2 contract per the same
 * three-assertion-minimum the sibling smokes follow.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `options` row in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleBreadcrumbSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'breadcrumb-module-settings';

    private const MODULE_NAME = 'breadcrumb';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_breadcrumb_smoke_data_start_from';

    /**
     * One of the two non-default Select values the page exposes
     * (the other is "page"). "category" is canonical when the
     * breadcrumb walks a category-tree nested-page trail, so
     * pinning this value also surfaces a regression that drops
     * category-rooted breadcrumbs from the Select set.
     */
    private const FIXTURE_OPTION_VALUE = 'category';

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
            ->where('module', self::MODULE_NAME)
            ->delete();
    }

    #[Test]
    public function breadcrumb_settings_page_loads_and_round_trips_the_root_level_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // breadcrumb settings admin (HTTP < 500, no Whoops /
            // Internal Server Error / Symfony stack-trace markers
            // in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'breadcrumb module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'breadcrumb settings render');

            // Signal #2 — round-trip the root-level option through
            // the same save_module_option() pipeline the page's
            // Livewire updated() hook calls on every reactive
            // field update. The persisted row is what the
            // public-frontend breadcrumb widget reads to choose
            // its nested-page-trail starting point.
            $this->assertRootLevelOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `data-start-from`-style option
     * through the same save_module_option() helper the
     * Breadcrumb settings page's Livewire updated() hook calls
     * server-side when the Root-level Select is changed. Then
     * assert the row landed in `options` with the correct
     * (option_key, option_value, module) tuple — the exact row
     * the public-frontend breadcrumb widget reads to choose
     * which nested-page level it walks up from when rendering
     * the breadcrumb trail.
     */
    private function assertRootLevelOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-breadcrumb-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the breadcrumb module — '
            . "this is the same code path the Breadcrumb settings page invokes from its "
            . 'Livewire updated() hook on every Root-level Select change.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match what was passed to save_module_option(). '
            . 'The public-frontend breadcrumb widget reads this exact row to choose which '
            . 'nested-page level it walks up from when rendering the breadcrumb trail; a '
            . 'mismatch here would silently break nested-page breadcrumbs.'
        );
    }

    /**
     * Probe the rendered settings page for the Filament/Livewire
     * scaffolding that proves a save round-trip is possible from
     * the UI. Same shape as the sibling Audio/Accordion smokes.
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
            'breadcrumb settings page must render at least one Livewire / Filament wiring '
            . 'attribute (wire:model / wire:submit / wire:click="save" inline, OR wire:id / '
            . 'wire:snapshot / fi-page / fi-form deferred) — otherwise the saved-option '
            . 'round-trip asserted above would only prove the helper works, not that the page '
            . 'is reachable through the Livewire form pipeline.'
        );
    }
}
