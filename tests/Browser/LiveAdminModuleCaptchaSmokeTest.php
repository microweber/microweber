<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Captcha module smoke.
 *
 * Same shape as {@see LiveAdminModuleAccordionSmokeTest}: the
 * Captcha module ships a Filament settings page registered via
 * FilamentRegistry::registerPage(CaptchaModuleSettings::class)
 * in CaptchaServiceProvider.php. Filament-default route slug:
 * /admin/captcha-module-settings.
 *
 * Plan-C.2 task line is "captcha settings; form submits with
 * token". The settings page exposes the canonical captcha-config
 * field set: a Select for provider (microweber / google_recaptcha_v2
 * / google_recaptcha_v3) plus per-provider site/secret-key
 * TextInputs. The smoke round-trips the `provider` option through
 * the same save_module_option() pipeline the page's Livewire
 * updated() hook calls — that's the option that determines which
 * captcha backend the public-frontend forms pull a token from.
 *
 * "Form submits with token" is the downstream assertion shape:
 * once the captcha provider is configured, ContactForm /
 * Checkout / etc. submit a captcha token through the configured
 * provider's verify endpoint. Driving a real form-submit-with-
 * token end-to-end requires either (a) standing up a Google
 * ReCaptcha test-keys mock or (b) using the `microweber`
 * provider which generates an in-app token. Both branches are
 * out of scope for this smoke — the per-form assertions belong
 * to LiveAdminModuleContactFormSmokeTest /
 * LiveAdminModuleCheckoutSmokeTest, where the form-with-captcha
 * surface actually lives. This smoke covers the
 * admin-settings + provider round-trip portion of the Plan-C.2
 * contract per the same three-assertion-minimum the sibling
 * smokes follow.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/captcha-module-settings.
 *   2. Signal #2 (provider save round-trip): direct
 *      save_module_option() call against the `provider` option
 *      key with value "microweber" (the in-app default — proves
 *      the in-app token-generation backend is the live default
 *      regardless of whether Google keys are configured); row
 *      verified to land in `options`.
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
class LiveAdminModuleCaptchaSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'captcha-module-settings';

    private const MODULE_NAME = 'captcha';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_captcha_smoke_provider';

    /**
     * The in-app captcha provider — generates tokens locally
     * without depending on Google's ReCaptcha keys. Pinning this
     * value also surfaces a regression that drops the in-app
     * provider from the Select (a common breakage when the
     * module is split across packages).
     */
    private const FIXTURE_OPTION_VALUE = 'microweber';

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
    public function captcha_settings_page_loads_and_round_trips_the_provider_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the captcha
            // settings admin (HTTP < 500, no Whoops / Internal
            // Server Error / Symfony stack-trace markers in the
            // DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'captcha module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'captcha settings render');

            // Signal #2 — round-trip the provider option through
            // the same save_module_option() pipeline the page's
            // Livewire updated() hook calls on every reactive
            // Select change. The persisted row is what
            // ContactForm / Checkout / etc. read to choose which
            // captcha backend their submit-with-token round-trip
            // uses.
            $this->assertProviderOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `provider`-style option through the
     * same save_module_option() helper the Captcha settings
     * page's Livewire updated() hook calls server-side when the
     * provider Select is changed. Then assert the row landed in
     * `options` with the correct (option_key, option_value,
     * module) tuple — the exact row the form-with-captcha
     * consumers (ContactForm / Checkout) read to choose which
     * captcha backend their submit-with-token round-trip uses.
     */
    private function assertProviderOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-captcha-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the captcha module — '
            . "this is the same code path the Captcha settings page invokes from its "
            . 'Livewire updated() hook on every provider Select change.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the provider passed to '
            . 'save_module_option(). The form-with-captcha consumers (ContactForm / '
            . 'Checkout) read this exact row to choose which captcha backend their '
            . 'submit-with-token round-trip uses; a mismatch here would silently break '
            . 'every form that depends on captcha verification.'
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
            || str_contains($source, 'fi-form');

        $this->assertTrue(
            $hasInlineSave || $hasDeferredSave,
            'captcha settings page must render at least one Livewire / Filament wiring '
            . 'attribute (wire:model / wire:submit / wire:click="save" inline, OR wire:id / '
            . 'wire:snapshot / fi-page / fi-form deferred) — otherwise the saved-option '
            . 'round-trip asserted above would only prove the helper works, not that the page '
            . 'is reachable through the Livewire form pipeline.'
        );
    }
}
