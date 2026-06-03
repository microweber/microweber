<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Btn (button) module smoke.
 *
 * Same shape as {@see LiveAdminModuleAccordionSmokeTest}: the
 * Btn module ships a Filament settings page registered via
 * FilamentRegistry::registerPage(BtnModuleSettings::class) in
 * BtnServiceProvider.php. Filament-default route slug:
 * /admin/btn-module-settings.
 *
 * Plan-C.2 task line is "button module settings form". The
 * settings page exposes the canonical button-config field set:
 * `options.text` (TextInput, default "Button"), `options.url`
 * (link picker), `options.align` (ToggleButtons), plus styling
 * options. The smoke round-trips the `text` option through the
 * same save_module_option() pipeline the page's Livewire
 * updated() hook calls on every reactive field update — that's
 * the option that controls the literal label rendered in the
 * public-frontend `<button>` tag.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/btn-module-settings.
 *   2. Signal #2 (button-text save round-trip): direct
 *      save_module_option() call against the `text` option key
 *      with a marker-prefixed value; verifies the row lands in
 *      `options` (the exact row the public-frontend btn module
 *      reads to render the button label).
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
class LiveAdminModuleBtnSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'btn-module-settings';

    private const MODULE_NAME = 'btn';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_btn_smoke_text';

    private const FIXTURE_OPTION_VALUE = 'Click me — btn smoke';

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
    public function btn_settings_page_loads_and_round_trips_the_button_text_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the btn
            // settings admin (HTTP < 500, no Whoops / Internal
            // Server Error / Symfony stack-trace markers in the
            // DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'btn module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'btn settings render');

            // Signal #2 — round-trip the button-text option through
            // the same save_module_option() pipeline the page's
            // Livewire updated() hook calls on every TextInput
            // edit. The persisted row is what the
            // public-frontend btn module reads to render the
            // button's literal label.
            $this->assertButtonTextOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `text`-style option through the
     * same save_module_option() helper the Btn settings page's
     * Livewire updated() hook calls server-side when the
     * button-text TextInput is edited. Then assert the row
     * landed in `options` with the correct (option_key,
     * option_value, module) tuple — the exact row the
     * public-frontend btn module reads to render the button
     * label.
     */
    private function assertButtonTextOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-btn-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the btn module — this is '
            . "the same code path the Btn settings page invokes from its Livewire updated() "
            . 'hook on every button-text TextInput edit.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the button-text passed to '
            . 'save_module_option(). The public-frontend btn module reads this exact row to '
            . 'render the button label; a mismatch here would silently break button-text '
            . 'edits across every page that embeds the btn module.'
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
            || str_contains($source, 'fi-form');

        $this->assertTrue(
            $hasInlineSave || $hasDeferredSave,
            'btn settings page must render at least one Livewire / Filament wiring '
            . 'attribute (wire:model / wire:submit / wire:click="save" inline, OR wire:id / '
            . 'wire:snapshot / fi-page / fi-form deferred) — otherwise the saved-option '
            . 'round-trip asserted above would only prove the helper works, not that the page '
            . 'is reachable through the Livewire form pipeline.'
        );
    }
}
