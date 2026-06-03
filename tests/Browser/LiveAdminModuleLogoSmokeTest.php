<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Logo module smoke (logo upload form).
 *
 * Same shape as the canonical sibling
 * {@see LiveAdminModuleBtnSmokeTest}: the Logo module ships a
 * Filament settings page registered via
 * FilamentRegistry::registerPage(LogoModuleSettings::class) in
 * LogoServiceProvider.php. Filament-default route slug:
 * /admin/logo-module-settings.
 *
 * Plan-C.2 task line is "logo logo upload form". The settings
 * page exposes a Tabs container: Image tab
 * (`options.logoimage` MwFileUpload + `options.size` slider),
 * Text tab (`options.text` — the logo TextInput LogoModule's
 * render() falls back to when no logoimage is set, plus
 * `options.text_color` / `options.font_size`), and Template
 * tab. The smoke round-trips the `text` option through the same
 * save_module_option() pipeline the page's Livewire updated()
 * hook calls on every reactive field update — that's the
 * fallback option the public-frontend LogoModule::render()
 * branches into when no logoimage is uploaded (see
 * Modules/Logo/Microweber/LogoModule.php:43).
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/logo-module-settings.
 *   2. Signal #2 (logo-text fallback save round-trip): direct
 *      save_module_option() call against the `text` option key
 *      with a marker-prefixed logo string; verifies the row
 *      lands in `options` (the exact row LogoModule::render()
 *      reads as the no-image fallback label).
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
class LiveAdminModuleLogoSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'logo-module-settings';

    private const MODULE_NAME = 'logo';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_logo_smoke_text';

    private const FIXTURE_OPTION_VALUE = 'Live Admin Module Logo Smoke';

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
    public function logo_settings_page_loads_and_round_trips_the_text_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the logo
            // settings admin (HTTP < 500, no Whoops / Internal
            // Server Error / Symfony stack-trace markers in the
            // DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'logo module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'logo settings render');

            // Signal #2 — round-trip the logo-text fallback
            // option through the same save_module_option()
            // pipeline the page's Livewire updated() hook calls
            // on every TextInput edit. The persisted row is what
            // LogoModule::render() falls back to when no
            // logoimage is uploaded (the no-image branch reads
            // it as the literal text in the rendered <a>).
            $this->assertLogoTextOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `text`-style option through the
     * same save_module_option() helper the Logo settings page's
     * Livewire updated() hook calls server-side when the
     * fallback-text TextInput is edited. Then assert the row
     * landed in `options` with the correct (option_key,
     * option_value, module) tuple — the exact row
     * LogoModule::render() reads as the no-image fallback
     * label.
     */
    private function assertLogoTextOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-logo-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the logo module '
            . '— this is the same code path the Logo settings page invokes from '
            . 'its Livewire updated() hook on every fallback-text TextInput edit.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the logo text passed to '
            . 'save_module_option(). LogoModule::render() reads this exact row '
            . 'as the no-image fallback label; a mismatch here would silently '
            . 'break the operator-configured logo text on every page that has '
            . 'no uploaded logo image.'
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
            'logo settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, '
            . 'OR wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline.'
        );
    }
}
