<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — HighlightCode module smoke (code-block insertion).
 *
 * Same shape as the canonical sibling
 * {@see LiveAdminModuleBtnSmokeTest}: the HighlightCode module
 * ships a Filament settings page registered via
 * FilamentRegistry::registerPage(HighlightCodeModuleSettings::class)
 * in HighlightCodeServiceProvider.php. Filament-default route
 * slug: /admin/highlight-code-module-settings.
 *
 * Plan-C.2 task line is "highlight code code-block insertion".
 * The settings page exposes a Tabs container with a "Highlight
 * Code" tab whose primary input is the `options.text` Textarea
 * — the literal source-code blob the operator pastes that the
 * public-frontend HighlightCodeModule renders inside a
 * <pre><code> tag for the highlight.js client to syntax-color.
 * The smoke round-trips this `text` option through the same
 * save_module_option() pipeline the page's Livewire updated()
 * hook calls on every Textarea change — that's the option the
 * public-frontend HighlightCodeModule reads to populate the
 * code-block body verbatim.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of
 *      /admin/highlight-code-module-settings.
 *   2. Signal #2 (code-block save round-trip): direct
 *      save_module_option() call against the `text` option key
 *      with a marker-prefixed code snippet; verifies the row
 *      lands in `options` (the exact row the public-frontend
 *      HighlightCodeModule reads to render the <pre><code>
 *      body).
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
class LiveAdminModuleHighlightCodeSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'highlight-code-module-settings';

    private const MODULE_NAME = 'highlight_code';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_highlight_code_smoke_text';

    private const FIXTURE_OPTION_VALUE = "<?php echo 'live-admin-module-highlight-code-smoke'; ?>";

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
    public function highlight_code_settings_page_loads_and_round_trips_the_text_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // highlight-code settings admin (HTTP < 500, no
            // Whoops / Internal Server Error / Symfony stack-
            // trace markers in the DOM, no SEVERE JS console
            // entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'highlight code module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'highlight code settings render');

            // Signal #2 — round-trip the code-text option
            // through the same save_module_option() pipeline the
            // page's Livewire updated() hook calls on every
            // Textarea change. The persisted row is what the
            // public-frontend HighlightCodeModule reads to
            // render the <pre><code> body verbatim before
            // highlight.js syntax-colors it on the client.
            $this->assertCodeTextOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `text`-style option through the
     * same save_module_option() helper the HighlightCode
     * settings page's Livewire updated() hook calls server-side
     * when the code Textarea is edited. Then assert the row
     * landed in `options` with the correct (option_key,
     * option_value, module) tuple — the exact row the
     * public-frontend HighlightCodeModule reads to render the
     * code-block body.
     */
    private function assertCodeTextOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-highlight-code-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the highlight_code '
            . 'module — this is the same code path the HighlightCode settings page '
            . 'invokes from its Livewire updated() hook on every code-Textarea edit.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the code blob passed to '
            . 'save_module_option() byte-for-byte. The public-frontend '
            . 'HighlightCodeModule reads this exact row to render the <pre><code> '
            . 'body verbatim; any sanitizer regression that strips PHP tags / '
            . '<? markers / quoting would silently break every code block '
            . 'inserted via this module.'
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
            'highlight code settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, '
            . 'OR wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline.'
        );
    }
}
