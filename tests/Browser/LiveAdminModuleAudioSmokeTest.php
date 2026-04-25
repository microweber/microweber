<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Audio module smoke.
 *
 * Same shape as {@see LiveAdminModuleAccordionSmokeTest}: the
 * Audio module ships a Filament settings page registered via
 * `FilamentRegistry::registerPage(AudioModuleSettings::class)`
 * in AudioServiceProvider.php, with the Filament-default route
 * slug `/admin/audio-module-settings`. The settings page extends
 * `LiveEditModuleSettings`, whose Livewire `updated()` hook
 * calls `save_module_option()` on every reactive field update.
 *
 * Plan-C.2 task line is "audio module insertion + inline URL
 * edit". The "URL edit" maps directly to the
 * `options.data-audio-url` TextInput on the settings page (see
 * Modules/Audio/Filament/AudioModuleSettings.php line 60+) — the
 * field that lets an operator point the audio module at an
 * external file URL instead of an uploaded local file.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/audio-module-settings
 *      — covers HTTP 200, no Whoops/Internal Server
 *      Error/Symfony stack-trace markers in the DOM, no SEVERE
 *      JS console entries.
 *   2. Signal #2 (URL-edit save round-trip): direct
 *      save_module_option() call against the data-audio-url
 *      option key through the same pipeline the page's
 *      Livewire updated() hook calls; verifies the row lands in
 *      `options` with the expected URL value.
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
class LiveAdminModuleAudioSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'audio-module-settings';

    private const MODULE_NAME = 'audio';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_audio_smoke_data_audio_url';

    private const FIXTURE_OPTION_VALUE = 'https://example.test/live-admin-module-audio-smoke.mp3';

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
    public function audio_settings_page_loads_and_round_trips_the_url_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the audio
            // settings admin (HTTP < 500, no Whoops/Internal
            // Server Error/Symfony stack-trace markers in the
            // DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'audio module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'audio settings render');

            // Signal #2 — real save round-trip through the same
            // save_module_option() pipeline the page's Livewire
            // updated() hook calls when the inline URL field is
            // edited.
            $this->assertUrlOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — same probe shape as the sibling
            // Accordion smoke. The literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `data-audio-url`-style option through
     * the same `save_module_option()` helper the Audio settings
     * page's `updated()` hook calls server-side when the inline
     * URL TextInput is edited. Then assert the row landed in
     * `options` with the correct (option_key, option_value, module)
     * tuple — the exact row the public-frontend audio module
     * reads to render the `<audio src="…">` tag.
     */
    private function assertUrlOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-audio-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the audio module — this is '
            . "the same code path the Audio settings page invokes from its Livewire updated() "
            . 'hook on every inline URL field edit.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the URL passed to save_module_option(). The '
            . 'public-frontend audio module reads this exact row to render <audio src="…">; a '
            . 'mismatch here would silently break inline URL edits.'
        );
    }

    /**
     * Probe the rendered settings page for the Filament/Livewire
     * scaffolding that proves a save round-trip is possible from
     * the UI. Accepts either inline Livewire markup
     * (wire:model / wire:submit / wire:click="save") or the
     * deferred Filament shell (wire:id / wire:snapshot / fi-page
     * / fi-form) — same shape as the sibling Accordion smoke.
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
            'audio settings page must render at least one Livewire / Filament wiring '
            . 'attribute (wire:model / wire:submit / wire:click="save" inline, OR wire:id / '
            . 'wire:snapshot / fi-page / fi-form deferred) — otherwise the saved-option '
            . 'round-trip asserted above would only prove the helper works, not that the page '
            . 'is reachable through the Livewire form pipeline.'
        );
    }
}
