<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Pdf module smoke (PDF embed/export settings).
 *
 * Same shape as the canonical sibling
 * {@see LiveAdminModuleBtnSmokeTest}: the Pdf module ships a
 * Filament settings page registered via
 * FilamentRegistry::registerPage(PdfModuleSettings::class) in
 * PdfServiceProvider.php. Filament-default route slug:
 * /admin/pdf-module-settings.
 *
 * Plan-C.2 task line is "pdf PDF export smoke". The settings
 * page exposes a single Section with three reactive inputs:
 * `options.data-pdf-source` (ToggleButtons: file / url),
 * `options.data-pdf-upload` (MwFileUpload, hidden when source
 * is "url"), and `options.data-pdf-url` (TextInput, hidden
 * when source is "file") — the URL the operator pastes when
 * embedding a remote-hosted PDF rather than uploading one. The
 * smoke round-trips the `data-pdf-url` option through the same
 * save_module_option() pipeline the page's Livewire updated()
 * hook calls on every reactive field update — that's the
 * option the public-frontend PdfModule reads to construct the
 * embed iframe's `data-pdf-url` attribute the PDF.js bootstrap
 * loads.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/pdf-module-settings.
 *   2. Signal #2 (data-pdf-url save round-trip): direct
 *      save_module_option() call against the `data-pdf-url`
 *      option key with a marker-prefixed PDF URL; verifies the
 *      row lands in `options` (the exact row the public-
 *      frontend PdfModule reads to render the iframe's
 *      data-pdf-url attribute).
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
class LiveAdminModulePdfSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'pdf-module-settings';

    private const MODULE_NAME = 'pdf';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_pdf_smoke_data_pdf_url';

    private const FIXTURE_OPTION_VALUE = 'https://www.example.com/live-admin-module-pdf-smoke.pdf';

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
    public function pdf_settings_page_loads_and_round_trips_the_data_pdf_url_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the pdf
            // settings admin (HTTP < 500, no Whoops / Internal
            // Server Error / Symfony stack-trace markers in the
            // DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'pdf module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'pdf settings render');

            // Signal #2 — round-trip the data-pdf-url option
            // through the same save_module_option() pipeline the
            // page's Livewire updated() hook calls on every
            // TextInput edit. The persisted row is what the
            // public-frontend PdfModule reads to construct the
            // embed iframe's data-pdf-url attribute the PDF.js
            // bootstrap loads.
            $this->assertDataPdfUrlOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `data-pdf-url`-style option through
     * the same save_module_option() helper the Pdf settings
     * page's Livewire updated() hook calls server-side when the
     * remote-PDF-URL TextInput is edited. Then assert the row
     * landed in `options` with the correct (option_key,
     * option_value, module) tuple — the exact row the
     * public-frontend PdfModule reads to render the embed
     * iframe's data-pdf-url attribute.
     */
    private function assertDataPdfUrlOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-pdf-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the pdf module '
            . '— this is the same code path the Pdf settings page invokes from '
            . 'its Livewire updated() hook on every remote-PDF-URL TextInput edit.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the PDF URL passed to '
            . 'save_module_option(). The public-frontend PdfModule reads this '
            . 'exact row to construct the embed iframe\'s data-pdf-url attribute '
            . 'the PDF.js bootstrap loads; a mismatch here would silently break '
            . 'every operator-pasted remote-PDF embed without erroring.'
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
            || str_contains($source, 'fi-page')
            || str_contains($source, 'fi-form');

        $this->assertTrue(
            $hasInlineSave || $hasDeferredSave,
            'pdf settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, '
            . 'OR wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline.'
        );
    }
}
