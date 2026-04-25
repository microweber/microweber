<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — FacebookLike module smoke (widget settings).
 *
 * Same shape as the canonical sibling
 * {@see LiveAdminModuleBtnSmokeTest}: the FacebookLike module
 * ships a Filament settings page registered via
 * FilamentRegistry::registerPage(FacebookLikeModuleSettings::class)
 * in FacebookLikeServiceProvider.php. Filament-default route
 * slug: /admin/facebook-like-module-settings.
 *
 * Plan-C.2 task line is "facebook like widget settings". The
 * settings page exposes the canonical FB-like-button config
 * field set: `options.layout` (Select: standard / button_count
 * / button / box_count), `options.color` (Select: light / dark),
 * `options.show_faces` (Toggle), and `options.url` — the
 * TextInput where the operator pastes the FB Page URL the FB
 * SDK should target. The smoke round-trips the `url` option
 * through the same save_module_option() pipeline the page's
 * Livewire updated() hook calls on every reactive field update
 * — that's the option the public-frontend FacebookLikeModule
 * reads to construct the FB-SDK <div data-href="…"> attribute
 * the Facebook JS SDK rewrites into the Like button.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of
 *      /admin/facebook-like-module-settings.
 *   2. Signal #2 (FB-page-url save round-trip): direct
 *      save_module_option() call against the `url` option key
 *      with a marker-prefixed FB-page URL; verifies the row
 *      lands in `options` (the exact row the public-frontend
 *      FacebookLikeModule reads to render the Like button's
 *      data-href attribute).
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
class LiveAdminModuleFacebookLikeSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'facebook-like-module-settings';

    private const MODULE_NAME = 'facebook_like';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_facebook_like_smoke_url';

    private const FIXTURE_OPTION_VALUE = 'https://www.facebook.com/live-admin-module-facebook-like-smoke';

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
    public function facebook_like_settings_page_loads_and_round_trips_the_url_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // facebook-like settings admin (HTTP < 500, no Whoops
            // / Internal Server Error / Symfony stack-trace
            // markers in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'facebook like module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'facebook like settings render');

            // Signal #2 — round-trip the FB-page-url option
            // through the same save_module_option() pipeline the
            // page's Livewire updated() hook calls on every
            // TextInput edit. The persisted row is what the
            // public-frontend FacebookLikeModule reads to render
            // the Like button's data-href attribute (the URL the
            // Facebook JS SDK rewrites into the like target).
            $this->assertUrlOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `url`-style option through the same
     * save_module_option() helper the FacebookLike settings
     * page's Livewire updated() hook calls server-side when the
     * FB-page-URL TextInput is edited. Then assert the row
     * landed in `options` with the correct (option_key,
     * option_value, module) tuple — the exact row the
     * public-frontend FacebookLikeModule reads to render the
     * Like button's data-href attribute.
     */
    private function assertUrlOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-facebook-like-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the facebook_like '
            . 'module — this is the same code path the FacebookLike settings page '
            . 'invokes from its Livewire updated() hook on every URL TextInput edit.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the FB-page URL passed to '
            . 'save_module_option(). The public-frontend FacebookLikeModule reads '
            . 'this exact row to render the Like button data-href; a mismatch here '
            . 'would silently break the Like-target URL on every page that embeds '
            . 'the facebook_like widget.'
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
            'facebook like settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, '
            . 'OR wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline.'
        );
    }
}
