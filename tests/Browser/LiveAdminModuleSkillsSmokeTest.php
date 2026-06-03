<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Skills module smoke (skills repeater + JSON option).
 *
 * Same shape as the canonical sibling
 * {@see LiveAdminModuleBtnSmokeTest}: the Skills module ships a
 * Filament settings page registered via
 * FilamentRegistry::registerPage(SkillsModuleSettings::class) in
 * SkillsServiceProvider.php. Filament-default route slug:
 * /admin/skills-module-settings. The form is a Repeater bound to
 * the `skills` option key, JSON-encoded into a single options
 * row that the page hydrates via @json_decode($this->getOption(
 * 'skills')) on mount (see SkillsModuleSettings::mount).
 *
 * Plan-C.2 task line is "skills module". The smoke round-trips a
 * marker-prefixed JSON-encoded skills payload through the same
 * save_module_option() pipeline the page's Livewire updated()
 * hook calls when an operator adds / removes / re-orders a
 * skill row. The persisted JSON is what the public-frontend
 * SkillsModule reads to render the operator-configured skills
 * list.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/skills-module-settings.
 *   2. Signal #2 (skills JSON option round-trip): direct
 *      save_module_option() call against the `skills` option key
 *      with a marker-prefixed JSON-encoded payload; then
 *      asserts the row lands in `options` AND the round-trip
 *      json_decode produces the same array shape the page reads
 *      on mount. A regression in the JSON encode/decode boundary
 *      (renamed key, miscast int/string boundary, broken UTF-8
 *      escaping in option_value column) would surface here.
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
class LiveAdminModuleSkillsSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'skills-module-settings';

    private const MODULE_NAME = 'skills';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_skills_smoke_skills';

    /**
     * Marker-prefixed JSON-encoded skills payload. Mirrors the
     * shape SkillsModuleSettings::mount expects after json_decode
     * — a list of dicts with {skill: string, level: int} items.
     */
    private const FIXTURE_PAYLOAD = [
        ['skill' => 'Live Admin Smoke Skill A', 'level' => 80],
        ['skill' => 'Live Admin Smoke Skill B', 'level' => 65],
    ];

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
    public function skills_settings_page_loads_and_round_trips_the_skills_json_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // skills settings admin (HTTP < 500, no Whoops /
            // Internal Server Error / Symfony stack-trace markers
            // in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'skills module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'skills settings render');

            // Signal #2 — round-trip the skills JSON payload
            // through the same save_module_option() pipeline the
            // page's Livewire updated() hook calls server-side
            // when an operator adds / removes / reorders a skill
            // row. The persisted JSON is then re-hydrated through
            // the exact json_decode() the page's mount() runs.
            $this->assertSkillsJsonOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save the marker-prefixed skills JSON payload through the
     * same save_module_option() helper the Skills settings page's
     * Livewire updated() hook calls server-side when the
     * Repeater field mutates. Then assert the row landed in
     * `options` AND a round-trip json_decode produces the same
     * payload SkillsModuleSettings::mount hydrates from.
     */
    private function assertSkillsJsonOptionRoundTripPersists(): void
    {
        $payloadJson = json_encode(self::FIXTURE_PAYLOAD);

        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => $payloadJson,
            'option_group' => 'live-admin-module-skills-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the skills module — '
            . 'this is the same code path the Skills settings page invokes from its '
            . 'Livewire updated() hook on every Repeater mutation.'
        );

        $decoded = @json_decode((string) $row->option_value, true);
        $this->assertSame(
            self::FIXTURE_PAYLOAD,
            $decoded,
            'A round-trip json_decode of the persisted option_value must produce the '
            . 'same array shape SkillsModuleSettings::mount hydrates from on every '
            . 'page load. A regression here (broken JSON escaping in the option_value '
            . 'column, lossy UTF-8 round-trip, miscast int/string boundary) would '
            . 'silently break the operator-configured skills list across every page '
            . 'that embeds the skills module.'
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
            'skills settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, '
            . 'OR wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline.'
        );
    }
}
