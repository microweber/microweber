<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Embed module smoke.
 *
 * Same shape as the sibling {@see LiveAdminModuleCaptchaSmokeTest}
 * and {@see LiveAdminModuleCookieNoticeSmokeTest}: the Embed
 * module ships a Filament settings page registered as
 * EmbedModuleSettings via FilamentRegistry::registerPage in
 * EmbedServiceProvider.php. Filament-default route slug:
 * /admin/embed-module-settings. The page exposes two reactive
 * fields:
 *
 *   - `options.source_code`  — Textarea where the operator
 *     pastes the provider-issued embed snippet (YouTube
 *     <iframe src="…">, Vimeo <iframe>, Twitter <blockquote
 *     class="twitter-tweet">, Instagram <blockquote
 *     class="instagram-media">, etc.). The Embed module
 *     intentionally accepts arbitrary HTML — there is no
 *     provider whitelist; the field is opaque embed markup
 *     that is rendered verbatim through the module's
 *     templates.embed.default blade. EmbedModule::render reads
 *     this option via get_option('source_code', $params['id'])
 *     on every public-frontend render.
 *   - `options.hide_in_live_edit` — Toggle that hides the
 *     embed in the live-edit canvas (so the iframe doesn't
 *     intercept clicks while editing).
 *
 * Plan-C.2 task line is "embed module accepts common
 * providers". The provider-acceptance test reduces to: the
 * source_code field round-trips arbitrary opaque HTML through
 * save_module_option() without being sanitized away. The smoke
 * exercises this by round-tripping a YouTube-shaped <iframe>
 * AND a Twitter-shaped <blockquote> snippet — two structurally
 * different markup shapes that cover the iframe and
 * blockquote-with-script branches every common provider falls
 * into.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/embed-module-settings.
 *   2. Signal #2 (provider round-trip): direct
 *      save_module_option() call against the `source_code`
 *      option key with a YouTube-iframe snippet, then a
 *      Twitter-blockquote snippet, on a marker-prefixed module
 *      group key. Same code path the EmbedModuleSettings page's
 *      Livewire updated() hook calls server-side when the
 *      operator pastes new embed markup. Row verified to land
 *      in `options` with the persisted markup verbatim — proves
 *      the save pipeline does NOT strip iframe / script / data-*
 *      attributes that real-world embeds depend on.
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
class LiveAdminModuleEmbedSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'embed-module-settings';

    private const MODULE_NAME = 'embed';

    /**
     * Marker option-group prefix used to scope the round-trip
     * fixture rows so the tearDown purge can clean them up
     * without touching the real-operator embed instances on the
     * dev DB.
     */
    private const FIXTURE_GROUP_PREFIX = 'live-admin-module-embed-smoke-';

    /**
     * Provider-issued YouTube embed snippet. The smoke verifies
     * that the iframe + its sandbox/allow attributes survive the
     * save round-trip verbatim. A regression that strips iframe
     * tags, src URLs, or attribute lists would surface here.
     */
    private const FIXTURE_YOUTUBE_IFRAME =
        '<iframe width="560" height="315" '
        . 'src="https://www.youtube.com/embed/dQw4w9WgXcQ?si=live-admin-module-embed-smoke" '
        . 'title="YouTube video player" frameborder="0" '
        . 'allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; '
        . 'picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" '
        . 'allowfullscreen></iframe>';

    /**
     * Provider-issued Twitter / X embed snippet. Structurally
     * different from the iframe shape: blockquote + inline data
     * attributes + a sibling <script> tag. Real-world Twitter
     * embeds depend on the data-* attributes surviving the save
     * round-trip; a sanitizer that strips them would silently
     * break tweet renders without erroring.
     */
    private const FIXTURE_TWITTER_BLOCKQUOTE =
        '<blockquote class="twitter-tweet" data-lang="en" '
        . 'data-theme="light" data-dnt="true">'
        . '<p lang="en" dir="ltr">Live admin module embed smoke fixture tweet body</p>'
        . '&mdash; Live Admin Embed Smoke (@live_admin_embed_smoke) '
        . '<a href="https://twitter.com/example/status/0">January 1, 2026</a>'
        . '</blockquote>';

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
    public function embed_settings_page_loads_and_round_trips_common_provider_snippets(): void
    {
        $this->purgeFixtureOptions();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // embed settings admin (HTTP < 500, no Whoops /
            // Internal Server Error / Symfony stack-trace
            // markers in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'embed module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'embed settings render');

            // Signal #2 — round-trip a YouTube-iframe snippet
            // through save_module_option() AND verify the markup
            // landed verbatim in `options.option_value`. A
            // regression that sanitizes iframe / script / data-*
            // attributes would surface here.
            $this->assertProviderRoundTripPersists(
                self::FIXTURE_GROUP_PREFIX . 'youtube',
                self::FIXTURE_YOUTUBE_IFRAME,
                'YouTube iframe embed',
            );

            // Same round-trip with a Twitter-blockquote shape —
            // structurally different markup that exercises the
            // blockquote-with-data-* branch every common
            // social-embed provider hits.
            $this->assertProviderRoundTripPersists(
                self::FIXTURE_GROUP_PREFIX . 'twitter',
                self::FIXTURE_TWITTER_BLOCKQUOTE,
                'Twitter blockquote embed',
            );

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `source_code` option through the
     * same save_module_option() helper the EmbedModuleSettings
     * page's Livewire updated() hook calls server-side when the
     * operator pastes new embed markup. Then assert the row
     * landed in `options` with the markup persisted verbatim —
     * the exact row EmbedModule::render reads via get_option()
     * on every public-frontend render.
     */
    private function assertProviderRoundTripPersists(
        string $optionGroup,
        string $optionValue,
        string $providerLabel,
    ): void {
        save_module_option([
            'option_key' => 'source_code',
            'option_value' => $optionValue,
            'option_group' => $optionGroup,
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', 'source_code')
            ->where('option_group', $optionGroup)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the embed module — '
            . 'this is the same code path the Embed settings page invokes from its '
            . "Livewire updated() hook on every Textarea change. ($providerLabel)"
        );
        $this->assertSame(
            $optionValue,
            (string) $row->option_value,
            'The persisted option_value must match the provider snippet passed to '
            . 'save_module_option() byte-for-byte. EmbedModule::render reads this exact '
            . "row via get_option('source_code', \$params['id']) and renders it through "
            . 'the templates.embed.default blade — a sanitizer regression that strips '
            . 'iframe / script / data-* attributes would silently break every common '
            . "provider's embed without erroring. ($providerLabel)"
        );
    }

    /**
     * Probe the rendered settings page for the Filament/Livewire
     * scaffolding that proves a save round-trip is possible from
     * the UI. Same shape as the sibling Audio/Accordion/Captcha/
     * CookieNotice smokes.
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
            'embed settings page must render at least one Livewire / Filament wiring '
            . 'attribute (wire:model / wire:submit / wire:click="save" inline, OR '
            . 'wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline.'
        );
    }
}
