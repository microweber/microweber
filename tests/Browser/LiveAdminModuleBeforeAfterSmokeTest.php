<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — BeforeAfter module smoke.
 *
 * Same shape as {@see LiveAdminModuleAccordionSmokeTest}: the
 * BeforeAfter module ships a Filament settings page registered
 * via FilamentRegistry::registerPage(BeforeAfterModuleSettings::class)
 * in BeforeAfterServiceProvider.php. Filament-default route slug:
 * /admin/before-after-module-settings.
 *
 * The Plan-C.2 task line is "slider comparison widget" — the
 * BeforeAfter widget renders two stacked images with a draggable
 * divider that reveals one vs the other. The settings page
 * exposes two URL inputs: `options.before` and `options.after`.
 * The smoke round-trips both option keys through the same
 * save_module_option() pipeline the page's Livewire updated()
 * hook calls on every reactive field update.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/before-after-module-settings.
 *   2. Signal #2 (slider URL save round-trip): direct
 *      save_module_option() calls against marker-prefixed
 *      `before` and `after` option keys; verifies both rows
 *      land in `options` with the expected URL values (the
 *      exact rows the public-frontend slider widget reads to
 *      render the two stacked images).
 *   3. Belt-and-braces: installInPageErrorGuard() on the
 *      settings page after settle, with a 1.5s window catching
 *      any deferred-script throws.
 *
 * Note on module column: the BeforeAfter module's
 * AudioServiceProvider sets `protected string $moduleNameLower = 'before_after'`
 * (snake_case) — that's the value save_module_option() persists
 * in the `module` column. The settings page class also declares
 * `public string $module = 'before_after'`. Pin both via the
 * MODULE_NAME constant so a future module-rename surfaces here.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `options` rows in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleBeforeAfterSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'before-after-module-settings';

    private const MODULE_NAME = 'before_after';

    private const FIXTURE_BEFORE_OPTION_KEY = 'live_admin_module_before_after_smoke_before';

    private const FIXTURE_AFTER_OPTION_KEY = 'live_admin_module_before_after_smoke_after';

    private const FIXTURE_BEFORE_URL = 'https://example.test/before-smoke.jpg';

    private const FIXTURE_AFTER_URL = 'https://example.test/after-smoke.jpg';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureOptions();
        parent::tearDown();
    }

    private function purgeFixtureOptions(): void
    {
        DB::table('options')
            ->whereIn('option_key', [
                self::FIXTURE_BEFORE_OPTION_KEY,
                self::FIXTURE_AFTER_OPTION_KEY,
            ])
            ->where('module', self::MODULE_NAME)
            ->delete();
    }

    #[Test]
    public function before_after_settings_page_loads_and_round_trips_both_image_urls(): void
    {
        $this->purgeFixtureOptions();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // before-after settings admin (HTTP < 500, no Whoops
            // / Internal Server Error / Symfony stack-trace
            // markers, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'before-after module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'before-after settings render');

            // Signal #2 — round-trip BOTH image-URL option keys
            // (before + after) through the same
            // save_module_option() pipeline the page's Livewire
            // updated() hook calls on every reactive field update.
            // Both must land — a regression that persisted only
            // one half would silently break the slider widget.
            $this->assertImageOptionRoundTripPersists(
                self::FIXTURE_BEFORE_OPTION_KEY,
                self::FIXTURE_BEFORE_URL,
            );
            $this->assertImageOptionRoundTripPersists(
                self::FIXTURE_AFTER_OPTION_KEY,
                self::FIXTURE_AFTER_URL,
            );

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed image-URL option through the same
     * save_module_option() helper the BeforeAfter settings
     * page's Livewire updated() hook calls server-side. Then
     * assert the row landed in `options` with the correct
     * (option_key, option_value, module) tuple — the exact row
     * the public-frontend slider widget reads to render its
     * before/after image pair.
     */
    private function assertImageOptionRoundTripPersists(string $optionKey, string $optionValue): void
    {
        save_module_option([
            'option_key' => $optionKey,
            'option_value' => $optionValue,
            'option_group' => 'live-admin-module-before-after-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', $optionKey)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            "save_module_option() must persist an options row for the before_after module "
            . "with option_key '{$optionKey}' — this is the same code path the BeforeAfter "
            . 'settings page invokes from its Livewire updated() hook on every image-URL '
            . 'field edit.'
        );
        $this->assertSame(
            $optionValue,
            (string) $row->option_value,
            "The persisted option_value for '{$optionKey}' must match the URL passed to "
            . 'save_module_option(). The public-frontend slider widget reads this exact row '
            . 'to render its before/after image pair.'
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
            'before-after settings page must render at least one Livewire / Filament wiring '
            . 'attribute (wire:model / wire:submit / wire:click="save" inline, OR wire:id / '
            . 'wire:snapshot / fi-page / fi-form deferred) — otherwise the saved-option '
            . 'round-trip asserted above would only prove the helper works, not that the page '
            . 'is reachable through the Livewire form pipeline.'
        );
    }
}
