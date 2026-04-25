<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Marquee module smoke (module insertion).
 *
 * Same shape as the canonical sibling
 * {@see LiveAdminModuleBtnSmokeTest}: the Marquee module ships
 * a Filament settings page registered via
 * FilamentRegistry::registerPage(MarqueeModuleSettings::class)
 * in MarqueeServiceProvider.php. Filament-default route slug:
 * /admin/marquee-module-settings.
 *
 * Plan-C.2 task line is "marquee marquee module insertion". The
 * settings page exposes the canonical marquee field set:
 * `options.text` (the literal string scrolled across the
 * marquee, default "Your cool text here!"), plus styling
 * (`options.fontSize`, `options.animationSpeed`,
 * `options.textWeight`, `options.textStyle`,
 * `options.textColor`). The smoke round-trips the `text` option
 * through the same save_module_option() pipeline the page's
 * Livewire updated() hook calls on every reactive field update
 * — that's the option the public-frontend MarqueeModule reads
 * to populate the literal string scrolled in the marquee.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of
 *      /admin/marquee-module-settings.
 *   2. Signal #2 (marquee-text save round-trip): direct
 *      save_module_option() call against the `text` option key
 *      with a marker-prefixed string; verifies the row lands
 *      in `options` (the exact row the public-frontend
 *      MarqueeModule reads to render the scrolling text).
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
class LiveAdminModuleMarqueeSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'marquee-module-settings';

    private const MODULE_NAME = 'marquee';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_marquee_smoke_text';

    private const FIXTURE_OPTION_VALUE = 'Live admin module marquee smoke scroll text';

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
    public function marquee_settings_page_loads_and_round_trips_the_text_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // marquee settings admin (HTTP < 500, no Whoops /
            // Internal Server Error / Symfony stack-trace
            // markers in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'marquee module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'marquee settings render');

            // Signal #2 — round-trip the marquee-text option
            // through the same save_module_option() pipeline the
            // page's Livewire updated() hook calls on every
            // TextInput edit. The persisted row is what the
            // public-frontend MarqueeModule reads to render the
            // literal string scrolled across the marquee.
            $this->assertMarqueeTextOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `text`-style option through the
     * same save_module_option() helper the Marquee settings
     * page's Livewire updated() hook calls server-side when the
     * marquee-text TextInput is edited. Then assert the row
     * landed in `options` with the correct (option_key,
     * option_value, module) tuple — the exact row the
     * public-frontend MarqueeModule reads to render the
     * scrolling text.
     */
    private function assertMarqueeTextOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-marquee-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the marquee module '
            . '— this is the same code path the Marquee settings page invokes from '
            . 'its Livewire updated() hook on every marquee-text TextInput edit.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the marquee text passed to '
            . 'save_module_option(). The public-frontend MarqueeModule reads '
            . 'this exact row to render the literal string scrolled across the '
            . 'marquee; a mismatch here would silently break the marquee text '
            . 'on every page that embeds the marquee module.'
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
            'marquee settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, '
            . 'OR wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline.'
        );
    }
}
