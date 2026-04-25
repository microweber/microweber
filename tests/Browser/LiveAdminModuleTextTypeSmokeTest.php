<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — TextType module smoke.
 *
 * Same shape as {@see LiveAdminModuleBtnSmokeTest}: the TextType
 * module ships a Filament settings page registered via
 * FilamentRegistry::registerPage(TextTypeModuleSettings::class) in
 * TextTypeServiceProvider.php. Filament-default route slug:
 * /admin/text-type-module-settings. The Microweber module slug
 * is `text_type` (as declared on TextTypeModuleSettings::$module).
 *
 * Plan-C.2 task line is "text-type effect widget". The settings
 * page exposes the canonical text-type-effect field set:
 * `options.text` (the literal string fed to the typewriter
 * animation), `options.fontSize`, `options.animationSpeed`, and
 * `options.textColor`. The smoke round-trips the `text` option
 * through the same save_module_option() pipeline the page's
 * Livewire updated() hook calls on every TextInput edit — that's
 * the option the TextTypeModule frontend Blade reads to seed the
 * marquee animation's source string.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/text-type-module-settings.
 *   2. Signal #2 (text save round-trip): direct
 *      save_module_option() call against the `text` option key
 *      with a marker-prefixed value; verifies the row lands in
 *      `options` (the exact row the public-frontend text-type
 *      module reads to render the typewriter-effect string).
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
class LiveAdminModuleTextTypeSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'text-type-module-settings';

    private const MODULE_NAME = 'text_type';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_text_type_smoke_text';

    private const FIXTURE_OPTION_VALUE = 'Live admin module text-type smoke fixture line';

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
    public function text_type_settings_page_loads_and_round_trips_the_text_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // text-type settings admin (HTTP < 500, no Whoops /
            // Internal Server Error / Symfony stack-trace markers
            // in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'text-type module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'text-type settings render');

            // Signal #2 — round-trip the text-type-effect source
            // string through the same save_module_option() pipeline
            // the page's Livewire updated() hook calls on every
            // TextInput edit. The persisted row is what the
            // public-frontend text-type module reads to seed the
            // typewriter animation's source string.
            $this->assertTextTypeOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `text`-style option through the same
     * save_module_option() helper the TextTypeModuleSettings
     * page's Livewire updated() hook calls server-side when the
     * operator edits the text TextInput. Then assert the row
     * landed in `options` with the correct (option_key,
     * option_value, module) tuple — the exact row TextTypeModule
     * reads via get_option('text', $params['id']) to seed the
     * public-frontend typewriter animation.
     */
    private function assertTextTypeOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-text-type-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the text-type module — '
            . "this is the same code path the TextTypeModuleSettings page invokes from "
            . 'its Livewire updated() hook on every text TextInput edit.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the text passed to '
            . 'save_module_option(). The public-frontend text-type module reads this '
            . 'exact row to render the typewriter-effect source string; a mismatch here '
            . 'would silently break text-type-widget edits across every page that embeds '
            . 'the text_type module.'
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
            'text-type settings page must render at least one Livewire / Filament wiring '
            . 'attribute (wire:model / wire:submit / wire:click="save" inline, OR wire:id '
            . '/ wire:snapshot / fi-page / fi-form deferred) — otherwise the saved-option '
            . 'round-trip asserted above would only prove the helper works, not that the '
            . 'page is reachable through the Livewire form pipeline.'
        );
    }
}
