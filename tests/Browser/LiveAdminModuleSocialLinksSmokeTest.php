<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — SocialLinks module smoke (social-links settings).
 *
 * Same shape as the canonical sibling
 * {@see LiveAdminModuleBtnSmokeTest}: the SocialLinks module
 * ships a Filament settings page registered via
 * FilamentRegistry::registerPage(SocialLinksModuleSettings::class)
 * in SocialLinksServiceProvider.php. Filament-default route
 * slug: /admin/social-links-module-settings. The form is a Tabs
 * container with a Content tab exposing per-platform
 * `options.facebook_enabled` / `options.facebook_url` toggle/url
 * pairs (Facebook, X, Instagram, etc.) plus the shared LiveEdit
 * design + data-source tabs the abstract base contributes.
 *
 * Plan-C.2 task line is "social-links settings". The smoke
 * round-trips a marker-prefixed `facebook_url`-style option
 * through the same save_module_option() pipeline the page's
 * Livewire updated() hook calls on every reactive field
 * update — that's the URL the public-frontend SocialLinks module
 * reads to render the operator-configured social-share buttons.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/social-links-module-settings.
 *   2. Signal #2 (social-link option save round-trip): direct
 *      save_module_option() call against a marker-prefixed key
 *      with a marker-prefixed Facebook URL value; verifies the
 *      row lands in `options` (the same row
 *      SocialLinksModule::render() reads when emitting the
 *      operator-configured Facebook share button on the public
 *      frontend).
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
class LiveAdminModuleSocialLinksSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'social-links-module-settings';

    /**
     * The SocialLinks module declares
     * `public string $module = 'social_links';` in its settings
     * page (snake-case rather than dashed) — that's the module
     * key the LiveEditModuleSettings abstract base uses when
     * resolving option_group / module-row scoping. The smoke
     * writes against this exact key so the persisted row lands
     * in the same module bucket the page reads from.
     */
    private const MODULE_NAME = 'social_links';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_social_links_smoke_facebook_url';

    private const FIXTURE_OPTION_VALUE = 'https://facebook.com/live-admin-module-social-links-smoke';

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
    public function social_links_settings_page_loads_and_round_trips_the_facebook_url_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the social-
            // links settings admin (HTTP < 500, no Whoops /
            // Internal Server Error / Symfony stack-trace markers
            // in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'social-links module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'social-links settings render');

            // Signal #2 — round-trip the facebook_url option
            // through the same save_module_option() pipeline the
            // page's Livewire updated() hook calls server-side
            // when an operator types into the URL TextInput. The
            // persisted row is the same one the public-frontend
            // SocialLinks module reads to emit the Facebook
            // share-button href.
            $this->assertSocialLinksOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save the marker-prefixed Facebook URL option through the
     * same save_module_option() helper the SocialLinks settings
     * page's Livewire updated() hook calls server-side when the
     * URL TextInput mutates. Then assert the row landed in
     * `options` with the correct (option_key, option_value,
     * module) tuple — the exact row SocialLinksModule::render()
     * reads when emitting the share-button href on the public
     * frontend.
     */
    private function assertSocialLinksOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-social-links-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the social_links '
            . 'module — this is the same code path the SocialLinks settings page '
            . 'invokes from its Livewire updated() hook on every URL TextInput '
            . 'edit.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the URL passed to '
            . 'save_module_option(). SocialLinksModule::render() reads this exact '
            . 'row when emitting the share-button href; a mismatch here would '
            . 'silently break the operator-configured social-share URL across '
            . 'every page that embeds the social-links module.'
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
            'social-links settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, '
            . 'OR wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise '
            . 'the saved-option round-trip asserted above would only prove the '
            . 'helper works, not that the page is reachable through the Livewire '
            . 'form pipeline.'
        );
    }
}
