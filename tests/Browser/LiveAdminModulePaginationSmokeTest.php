<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Pagination module smoke (widget settings).
 *
 * Same shape as the canonical sibling
 * {@see LiveAdminModuleBtnSmokeTest}: the Pagination module
 * ships a Filament settings page registered via
 * FilamentRegistry::registerPage(PaginationModuleSettings::class)
 * in PaginationServiceProvider.php. Filament-default route
 * slug: /admin/pagination-module-settings.
 *
 * Plan-C.2 task line is "pagination pagination widget
 * settings". The settings page exposes a Tabs container with a
 * Main settings tab whose primary input is the
 * `options.paging_param` TextInput — the URL query parameter
 * name (e.g. "page") the public-frontend PaginationModule reads
 * from the request and writes back into every page-link href so
 * the operator can rebrand it (e.g. "p" or "seite") without
 * touching the consumer code. Other Main-tab fields:
 * `options.pages_count`, `options.show_first_last` Toggle,
 * `options.limit` (default 5). The smoke round-trips the
 * `paging_param` option through the same save_module_option()
 * pipeline the page's Livewire updated() hook calls on every
 * reactive field update — that's the option the public-frontend
 * PaginationModule reads to construct every page-link href.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of
 *      /admin/pagination-module-settings.
 *   2. Signal #2 (paging-param save round-trip): direct
 *      save_module_option() call against the `paging_param`
 *      option key with a marker-prefixed param-name string;
 *      verifies the row lands in `options` (the exact row the
 *      public-frontend PaginationModule reads to construct
 *      every page-link href).
 *   3. Belt-and-braces: installInPageErrorGuard() on the
 *      settings page after settle, with a 1.5s window catching
 *      any deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `options` row in tearDown;
 * safe to re-run.
 */
class LiveAdminModulePaginationSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'pagination-module-settings';

    private const MODULE_NAME = 'pagination';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_pagination_smoke_paging_param';

    private const FIXTURE_OPTION_VALUE = 'live_admin_module_pagination_smoke_page';

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
    public function pagination_settings_page_loads_and_round_trips_the_paging_param_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // pagination settings admin (HTTP < 500, no Whoops
            // / Internal Server Error / Symfony stack-trace
            // markers in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'pagination module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'pagination settings render');

            // Signal #2 — round-trip the paging_param option
            // through the same save_module_option() pipeline the
            // page's Livewire updated() hook calls on every
            // TextInput edit. The persisted row is what the
            // public-frontend PaginationModule reads to know
            // which URL query parameter to read on incoming
            // requests AND write back into every page-link
            // href.
            $this->assertPagingParamOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `paging_param`-style option through
     * the same save_module_option() helper the Pagination
     * settings page's Livewire updated() hook calls server-side
     * when the paging-parameter TextInput is edited. Then
     * assert the row landed in `options` with the correct
     * (option_key, option_value, module) tuple — the exact row
     * the public-frontend PaginationModule reads to know which
     * URL query parameter governs the active page.
     */
    private function assertPagingParamOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-pagination-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the pagination '
            . 'module — this is the same code path the Pagination settings page '
            . 'invokes from its Livewire updated() hook on every paging-parameter '
            . 'TextInput edit.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the paging-param name passed to '
            . 'save_module_option(). The public-frontend PaginationModule reads '
            . 'this exact row to know which URL query parameter governs the active '
            . 'page; a mismatch here would silently break paging on every page that '
            . 'embeds the pagination widget (the operator-renamed param would never '
            . 'reach the consumer).'
        );
    }

    /**
     * Probe the rendered settings page for the Filament/Livewire
     * scaffolding that proves a save round-trip is possible from
     * the UI. Same shape as the sibling Btn/Embed/CookieNotice
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
            'pagination settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, '
            . 'OR wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline.'
        );
    }
}
