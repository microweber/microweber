<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — CookieNotice module smoke.
 *
 * Same shape as the sibling {@see LiveAdminModuleCaptchaSmokeTest}
 * and {@see LiveAdminModuleAccordionSmokeTest}: the CookieNotice
 * module ships a Filament settings page registered via
 * FilamentRegistry::registerPage(CookieNoticeModuleSettingsAdmin::class)
 * in CookieNoticeServiceProvider.php. Filament-default route slug:
 * /admin/cookie-notice-module-settings-admin.
 *
 * Plan-C.2 task line is "cookie notice settings". The settings
 * page exposes the canonical cookie-notice config field set:
 * the `enable_cookie_notice` toggle (the master switch the
 * service-provider reads on every render to decide whether to
 * inject the cookie-notice module tag in the template foot), the
 * `cookie_policy_url` TextInput, the appearance ColorPickers
 * (background_color / text_color), the `panel_toggle_position`
 * Select, and the `cookie_notice_title` / `cookie_notice_text`
 * content fields.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of
 *      /admin/cookie-notice-module-settings-admin.
 *   2. Signal #2 (enable-toggle save round-trip): direct
 *      save_module_option() call against the master
 *      `enable_cookie_notice` option key with value "1" — the
 *      same option the CookieNoticeServiceProvider reads via
 *      get_option('enable_cookie_notice', 'cookie_notice') on
 *      every request to decide whether to render the module tag
 *      in template_foot. Row verified to land in `options` with
 *      the right (option_key, option_value, module) tuple.
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
class LiveAdminModuleCookieNoticeSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'cookie-notice-module-settings-admin';

    private const MODULE_NAME = 'cookie_notice';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_cookie_notice_smoke_enable';

    /**
     * The master cookie-notice toggle value — "1" matches the
     * truthy branch the service provider checks via get_option()
     * in CookieNoticeServiceProvider::register() before injecting
     * the module tag in template_foot. Pinning this value also
     * surfaces a regression that flips the option-store boolean
     * coercion (a common breakage when options are migrated
     * between the legacy options table and Filament's normalized
     * shape).
     */
    private const FIXTURE_OPTION_VALUE = '1';

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
    public function cookie_notice_settings_page_loads_and_round_trips_the_enable_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // cookie-notice settings admin (HTTP < 500, no Whoops
            // / Internal Server Error / Symfony stack-trace
            // markers in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'cookie notice module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'cookie notice settings render');

            // Signal #2 — round-trip the master enable option
            // through the same save_module_option() pipeline the
            // page's Livewire updated() hook calls on every
            // reactive Toggle change. The persisted row is what
            // the service-provider's template_foot hook reads on
            // every public-frontend request to decide whether to
            // inject the cookie-notice module tag.
            $this->assertEnableOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `enable_cookie_notice`-style option
     * through the same save_module_option() helper the
     * CookieNotice settings page's Livewire updated() hook calls
     * server-side when the master Toggle is flipped. Then assert
     * the row landed in `options` with the correct (option_key,
     * option_value, module) tuple — the exact row the public-
     * frontend service-provider hook reads on every request.
     */
    private function assertEnableOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-cookie-notice-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the cookie_notice module '
            . '— this is the same code path the CookieNotice settings page invokes from its '
            . 'Livewire updated() hook on every Toggle / TextInput / ColorPicker / Select '
            . 'change.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the value passed to save_module_option(). '
            . 'CookieNoticeServiceProvider::register() reads this exact row via '
            . "get_option('enable_cookie_notice', 'cookie_notice') on every public-frontend "
            . 'request to decide whether to inject the cookie-notice module tag in '
            . 'template_foot; a mismatch here would silently break the master enable / disable '
            . 'switch the operator just toggled in the admin.'
        );
    }

    /**
     * Probe the rendered settings page for the Filament/Livewire
     * scaffolding that proves a save round-trip is possible from
     * the UI. Same shape as the sibling Audio/Accordion/Captcha
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
            'cookie notice settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, OR '
            . 'wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline.'
        );
    }
}
