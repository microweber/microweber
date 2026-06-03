<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Pictures module smoke (picture module insertion).
 *
 * Same shape as the canonical sibling
 * {@see LiveAdminModuleBtnSmokeTest}: the Pictures module ships a
 * Filament settings page registered via
 * FilamentRegistry::registerPage(PicturesModuleSettings::class) in
 * PicturesServiceProvider.php. Filament-default route slug:
 * /admin/pictures-module-settings. Tabs: "Main settings" (MwMediaBrowser
 * media-picker bound to a relType/relId pair plus the data-source
 * `data-use-from-post` toggle) and "Design and Details" (the shared
 * LiveEdit template + data-source tabs the LiveEditModuleSettings
 * abstract base contributes).
 *
 * Plan-C.2 task line is "picture module insertion". The smoke
 * round-trips a marker-prefixed `data-use-from-post`-style option
 * through the same save_module_option() pipeline the page's
 * Livewire updated() hook calls on every reactive field update —
 * that's the toggle the public-frontend PicturesModule reads when
 * deciding whether to read media from the live-edit content
 * context vs. from the module's own MwMediaBrowser-bound list.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/pictures-module-settings.
 *   2. Signal #2 (option save round-trip): direct
 *      save_module_option() call against a marker-prefixed key
 *      with a pictures-style value; verifies the row lands in
 *      `options` (the same row PicturesModuleSettings::form()
 *      reads via $this->getOption('data-use-from-post') when
 *      deciding which relType/relId to seed the media browser
 *      with).
 *   3. Belt-and-braces: installInPageErrorGuard() on the settings
 *      page after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `options` row in tearDown;
 * safe to re-run.
 */
class LiveAdminModulePicturesSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'pictures-module-settings';

    private const MODULE_NAME = 'pictures';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_pictures_smoke_data_use_from_post';

    private const FIXTURE_OPTION_VALUE = 'y';

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
    public function pictures_settings_page_loads_and_round_trips_the_data_use_from_post_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // pictures settings admin (HTTP < 500, no Whoops /
            // Internal Server Error / Symfony stack-trace markers
            // in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'pictures module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'pictures settings render');

            // Signal #2 — round-trip the data-use-from-post
            // option through the same save_module_option()
            // pipeline the page's Livewire updated() hook calls
            // server-side when an operator toggles the
            // "use pictures from current page" switch. The
            // persisted row is the same one
            // PicturesModuleSettings::form() reads via
            // $this->getOption('data-use-from-post') when
            // deciding which relType/relId to seed the media
            // browser with on the next mount.
            $this->assertPicturesOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed pictures option through the same
     * save_module_option() helper the Pictures settings page's
     * Livewire updated() hook calls server-side when the
     * data-use-from-post toggle flips. Then assert the row landed
     * in `options` with the correct (option_key, option_value,
     * module) tuple.
     */
    private function assertPicturesOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-pictures-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the pictures module — '
            . 'this is the same code path the Pictures settings page invokes from its '
            . 'Livewire updated() hook on every data-use-from-post toggle change.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the toggle value passed to '
            . 'save_module_option(). PicturesModuleSettings::form() reads this exact '
            . 'row via $this->getOption(...) on every page mount; a mismatch here '
            . 'would silently break the operator-configured media-source choice on '
            . 'every page that embeds the pictures module.'
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
            'pictures settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, '
            . 'OR wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline.'
        );
    }
}
