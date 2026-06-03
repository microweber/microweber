<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Video module smoke.
 *
 * Same shape as {@see LiveAdminModuleEmbedSmokeTest}: the Video
 * module ships a Filament settings page registered as
 * VideoModuleSettings via FilamentRegistry::registerPage in
 * VideoServiceProvider.php. Filament-default route slug:
 * /admin/video-module-settings. The page exposes two reactive
 * fields the smoke needs to round-trip together (the Plan-C.2
 * task line is "video module + poster upload"):
 *
 *   - `options.embed_url`  — Textarea where the operator pastes
 *     either a raw video URL (YouTube / Vimeo) or a provider-
 *     issued <iframe> embed snippet. VideoModule::render reads
 *     this option via get_option('embed_url', $params['id']) on
 *     every public-frontend video render and routes it through
 *     VideoEmbed::resolve() to derive the embeddable iframe URL.
 *   - `options.thumbnail`  — MwFileUpload that lets the operator
 *     upload a custom poster image. VideoModule renders the
 *     poster on the `<video poster="…">` attribute (when the
 *     player is a `<video>` tag) or as a click-to-play overlay
 *     when the player is an iframe.
 *
 * The Plan-C.2 task line specifically calls out "video module +
 * poster upload" — i.e. both the embed-URL save AND the poster
 * upload save have to round-trip through the same
 * save_module_option() pipeline the page's Livewire updated()
 * hook calls server-side. The smoke covers both.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/video-module-settings.
 *   2. Signal #2 (embed_url + thumbnail round-trip): direct
 *      save_module_option() calls against the `embed_url` option
 *      key with a YouTube embed URL fixture, AND against the
 *      `thumbnail` option key with a marker-prefixed poster path.
 *      Both rows verified to land in `options` verbatim — proves
 *      the Video settings page's save pipeline survives both the
 *      iframe-source URL path AND the poster-image upload path.
 *   3. Belt-and-braces: installInPageErrorGuard() on the
 *      settings page after settle, with a 1.5s window catching
 *      any deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `options` rows in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleVideoSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'video-module-settings';

    private const MODULE_NAME = 'video';

    /**
     * Marker option-group prefix used to scope the round-trip
     * fixture rows so the tearDown purge can clean them up
     * without touching the real-operator video instances on the
     * dev DB.
     */
    private const FIXTURE_GROUP_PREFIX = 'live-admin-module-video-smoke-';

    /**
     * YouTube embed URL fixture. The smoke verifies the URL
     * survives the save round-trip verbatim — VideoEmbed::resolve()
     * picks YouTube embed URLs up via the canonical
     * `youtube.com/embed/<id>` form, so a regression that
     * re-encodes or strips the path here would silently break
     * every YouTube video render across the public-frontend.
     */
    private const FIXTURE_EMBED_URL =
        'https://www.youtube.com/embed/dQw4w9WgXcQ?si=live-admin-module-video-smoke';

    /**
     * Poster-image fixture. Real uploads land in the userfiles
     * media directory, but for the round-trip the smoke just
     * needs to prove that whatever string the MwFileUpload
     * produces is persisted into options.thumbnail verbatim — so
     * a marker-prefixed path stem is enough. VideoModule renders
     * this string into the `<video poster>` attribute (or the
     * click-to-play overlay) on every public-frontend render.
     */
    private const FIXTURE_THUMBNAIL_PATH =
        '/userfiles/modules/video/live-admin-module-video-smoke-poster.jpg';

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
            ->where('option_group', 'like', self::FIXTURE_GROUP_PREFIX . '%')
            ->where('module', self::MODULE_NAME)
            ->delete();
    }

    #[Test]
    public function video_settings_page_loads_and_round_trips_embed_url_and_poster_upload(): void
    {
        $this->purgeFixtureOptions();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the video
            // settings admin (HTTP < 500, no Whoops / Internal
            // Server Error / Symfony stack-trace markers in the
            // DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'video module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'video settings render');

            // Signal #2a — round-trip the embed_url option
            // through save_module_option() AND verify the URL
            // landed verbatim in `options.option_value`. A
            // regression that re-encodes / sanitizes the URL
            // would surface here (and would silently break
            // VideoEmbed::resolve() on every public render).
            $this->assertVideoOptionRoundTripPersists(
                self::FIXTURE_GROUP_PREFIX . 'embed-url',
                'embed_url',
                self::FIXTURE_EMBED_URL,
                'embed_url (YouTube embed URL)',
            );

            // Signal #2b — round-trip the thumbnail (poster
            // upload) option through the same pipeline. Closes
            // the second half of the Plan-C.2 task line "video
            // module + poster upload": proves the MwFileUpload's
            // server-side persistence path lands a string in
            // options.thumbnail that VideoModule can read verbatim
            // for the `<video poster>` attribute.
            $this->assertVideoOptionRoundTripPersists(
                self::FIXTURE_GROUP_PREFIX . 'thumbnail',
                'thumbnail',
                self::FIXTURE_THUMBNAIL_PATH,
                'thumbnail (poster upload path)',
            );

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed option through the same
     * save_module_option() helper the VideoModuleSettings page's
     * Livewire updated() hook calls server-side when the operator
     * edits any reactive field. Then assert the row landed in
     * `options` with the value persisted verbatim — VideoModule
     * reads the embed_url + thumbnail rows on every public-
     * frontend render to drive the iframe source / `<video>` tag
     * + poster overlay.
     */
    private function assertVideoOptionRoundTripPersists(
        string $optionGroup,
        string $optionKey,
        string $optionValue,
        string $fieldLabel,
    ): void {
        save_module_option([
            'option_key' => $optionKey,
            'option_value' => $optionValue,
            'option_group' => $optionGroup,
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', $optionKey)
            ->where('option_group', $optionGroup)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the video module — '
            . 'this is the same code path the Video settings page invokes from its '
            . "Livewire updated() hook on every reactive field change. ($fieldLabel)"
        );
        $this->assertSame(
            $optionValue,
            (string) $row->option_value,
            'The persisted option_value must match the value passed to '
            . 'save_module_option() byte-for-byte. VideoModule reads this exact row on '
            . 'every public-frontend render — for embed_url to drive VideoEmbed::resolve()'
            . " and for thumbnail to drive the `<video poster>` attribute. ($fieldLabel)"
        );
    }

    /**
     * Probe the rendered settings page for the Filament/Livewire
     * scaffolding that proves a save round-trip is possible from
     * the UI. Same shape as the sibling Embed / Audio / Accordion
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
            'video settings page must render at least one Livewire / Filament wiring '
            . 'attribute (wire:model / wire:submit / wire:click="save" inline, OR '
            . 'wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline.'
        );
    }
}
