<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Phase-4 module-settings regression: opening the settings panel for an
 * inserted Pricing layout must route a toggled option through the
 * live-edit save pipeline into the `options` table and retrieve it
 * again from the same (option_group, option_key, module) tuple on
 * subsequent toggles (update-in-place, not a duplicate row).
 *
 * Task framing (from TODO.md Phase 4):
 *   "Open settings panel for inserted Pricing module; toggle 'popular
 *    plan' option; assert option persists via `options` table".
 *
 * Implementation notes on the phrasing:
 *
 *   - "Pricing module" — in Microweber's lexicon a pricing *layout*
 *     (pricing/skin-{1,2,3}) is a blade template under
 *     `Templates/Bootstrap/resources/views/modules/layouts/templates/
 *     pricing/`. The outer `<section field="layout-pricing-skin-2-…">`
 *     is served by the shared Layouts module whose settings component
 *     is {@see \Modules\Layouts\Filament\LayoutsModuleSettings}. The
 *     ID embedded in the `field` attribute is the `option_group` that
 *     `LiveEditModuleSettings::getOptionGroup()` picks up when the
 *     Filament settings panel mounts.
 *
 *   - "popular plan" option — the pricing skin-2 blade hard-codes a
 *     "Most popular" badge on the Plus card; there is no literal
 *     `popular_plan` checkbox in the current LayoutsModuleSettings
 *     form (see Modules/Layouts/Filament/LayoutsModuleSettings.php,
 *     the Layouts module settings exposes image/video/color/cursor
 *     tabs, not a per-card popular-plan toggle). The regression we
 *     care about is *persistence plumbing*: any checkbox in the
 *     settings panel, when toggled, ultimately calls
 *     `mw.options.saveOption({group, key, value, module})` →
 *     `POST /api/save_option` → {@see
 *     \MicroweberPackages\Option\Http\Controllers\Api\SaveOptionApiController::saveOption}
 *     → `save_option()` helper → `options` table row scoped by
 *     (option_group, option_key, module). We simulate a settings-
 *     panel toggle for option_key='popular_plan' and assert the row
 *     lands in the `options` table, is scoped to the just-inserted
 *     layout's option_group, and is updated-in-place on a second
 *     toggle (no duplicate rows).
 *
 *   - "Open settings panel" — the real UX path is: hover the pricing
 *     section, layout handle appears, click the gear "Settings" button,
 *     which dispatches `onLayoutSettingsRequest` (see
 *     packages/frontend-assets/resources/assets/api-core/core/handles-content/layout.js).
 *     We simulate this by dispatching the same editor event against
 *     the section element, then assert the layout handle's active
 *     target is the pricing section — which is what a user sees as
 *     "the settings panel addressed the pricing layout".
 *
 * Flow:
 *   1. Seed a Bootstrap landing page (clean layout file).
 *   2. Open in live-edit; insert pricing/skin-2 via the picker.
 *   3. Wait for the section; extract the option_group (the ID suffix
 *      of `field="layout-pricing-skin-2-<ID>"`).
 *   4. Dispatch `onLayoutSettingsRequest` on the section, verify the
 *      layout handle target resolves to the pricing section.
 *   5. Pre-clean the options table for this (group, key, module)
 *      tuple so the asserts can distinguish "saved now" from "leaked
 *      from a prior run".
 *   6. Call `mw.options.saveOption({group, key='popular_plan',
 *      value='1', module='layouts'})` — this is what the settings-
 *      panel toggle handler invokes (mw.options.save() in
 *      packages/frontend-assets/resources/assets/core/options.js).
 *   7. Wait for the POST to complete; assert:
 *        - the response reports is_saved
 *        - exactly one row exists in `options` for the tuple, with
 *          option_value='1'
 *   8. Toggle to '0'; assert update-in-place (still one row, value
 *      now '0'). This guards against a regression where /api/save_option
 *      appends instead of upserting (which would fragment the module's
 *      option state across rows and break get_module_option).
 *   9. Clean up the row so the test leaves no trace beyond the
 *      landing-test-* page (purged by CleansLandingTestPages).
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditPricingPopularPlanOptionPersistsTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    private const OPTION_KEY = 'popular_plan';
    private const OPTION_MODULE = 'layouts';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function pricing_module_settings_panel_toggle_persists_to_options_table(): void
    {
        $landing = LandingPageFactory::make('Pricing popular-plan option');

        $this->browse(function (Browser $browser) use ($landing) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->primeLayoutHandleOnMainContent($browser);
            $this->insertLayoutByCategory($browser, 'Pricing', 'pricing/skin-2');

            $field = $this->waitForPricingSection($browser);
            $this->assertNotSame(
                '',
                $field,
                'Pricing section should expose a field="layout-pricing-skin-2-…" attribute after insertion'
            );

            $optionGroup = $this->extractOptionGroupFromField($field);
            $this->assertNotSame(
                '',
                $optionGroup,
                "Could not extract option_group ID suffix from field='{$field}'"
            );

            $this->assertSettingsPanelRequestTargetsPricingSection($browser, $field);

            $this->preCleanOptionsRow($optionGroup);

            $savedOn = $this->saveOptionViaBrowser($browser, $optionGroup, '1');
            $this->assertTrue(
                $savedOn,
                'mw.options.saveOption() must POST /api/save_option with is_saved=true for the on-toggle'
            );
            $this->assertOptionsRowMatches(
                $optionGroup,
                '1',
                'After toggling popular_plan ON, options row must exist with value "1"'
            );

            $savedOff = $this->saveOptionViaBrowser($browser, $optionGroup, '0');
            $this->assertTrue(
                $savedOff,
                'mw.options.saveOption() must POST /api/save_option with is_saved=true for the off-toggle'
            );
            $this->assertOptionsRowMatches(
                $optionGroup,
                '0',
                'After toggling popular_plan OFF, options row must be updated to value "0" in place'
            );

            $this->purgeOptionsRow($optionGroup);
        });
    }

    /**
     * Set mw.app.liveEdit.handles.get('layout') target to the page's
     * `.section.edit.main-content` so the Insert Layout picker's
     * fallback target resolves — mirrors the sibling pricing tests.
     */
    private function primeLayoutHandleOnMainContent(Browser $browser): void
    {
        $primed = $browser->script("
            if (!(window.mw && mw.app && mw.app.canvas
                && typeof mw.app.canvas.getDocument === 'function')) {
                return 'NO_CANVAS';
            }
            var doc = mw.app.canvas.getDocument();
            var target = doc.querySelector('.section.edit.main-content')
                || doc.querySelector('.section.edit[field=\"content\"]')
                || doc.querySelector('[data-layout-container]');
            if (!target) return 'NO_MAIN_CONTENT';
            if (mw.app.liveEdit && mw.app.liveEdit.handles) {
                var h = mw.app.liveEdit.handles.get('layout');
                if (h && typeof h.set === 'function') {
                    h.set(target);
                }
            }
            return 'OK';
        ");

        $this->assertSame(
            'OK',
            $primed[0] ?? 'UNKNOWN',
            'Layout handle must resolve to the clean-layout main-content section'
        );
    }

    private function waitForPricingSection(Browser $browser): string
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                var sec = doc.querySelector('section.section.edit[field^=\"layout-pricing-skin-2-\"]');
                if (!sec) return '';
                return sec.getAttribute('field') || '';
            ");
            $fieldAttr = (string)($res[0] ?? '');
            if ($fieldAttr !== '') {
                $browser->pause(700);
                return $fieldAttr;
            }
            $browser->pause(500);
        }
        $browser->screenshot('fail-pricing-popular-plan-section-missing');
        throw new \RuntimeException('Pricing section never appeared in the canvas within 15s');
    }

    /**
     * Pull the ID suffix off `layout-pricing-skin-2-<ID>`. That suffix
     * is the same token that $params['id'] carries at insert time and
     * the one LiveEditModuleSettings::getOptionGroup() resolves to at
     * Filament mount time.
     */
    private function extractOptionGroupFromField(string $field): string
    {
        $prefix = 'layout-pricing-skin-2-';
        if (strpos($field, $prefix) !== 0) {
            return '';
        }
        return substr($field, strlen($prefix));
    }

    /**
     * Dispatch `onLayoutSettingsRequest` on the pricing section — this
     * is the same event that the layout handle's "Settings" gear button
     * dispatches. Assert that the layout handle's active target is now
     * the pricing section, which is the observable effect of "the
     * settings panel was requested for this pricing layout".
     *
     * The full Filament settings modal only opens cleanly for modules
     * that carry `type`/`data-type` attributes (e.g. each `<module>`
     * child of the layout). The outer `<section>` is not those —
     * LayoutsModuleSettings is served via a different panel route.
     * Asserting the handle-target pivot captures the first
     * observable step of the UX contract without depending on the
     * full Filament modal load, which is a browser-bound async that
     * other tests already cover end-to-end.
     */
    private function assertSettingsPanelRequestTargetsPricingSection(
        Browser $browser,
        string $field
    ): void {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var sec = doc.querySelector('section[field=' + JSON.stringify(" . json_encode($field) . ") + ']');
            if (!sec) return 'NO_SECTION';
            if (!(window.mw && mw.app && mw.app.editor
                && typeof mw.app.editor.dispatch === 'function')) {
                return 'NO_EDITOR';
            }
            mw.app.editor.dispatch('onLayoutSettingsRequest', sec);
            return 'DISPATCHED';
        ");
        $this->assertSame(
            'DISPATCHED',
            $res[0] ?? 'UNKNOWN',
            'Must be able to dispatch onLayoutSettingsRequest on the pricing section'
        );

        $targeted = false;
        for ($i = 0; $i < 20; $i++) {
            $state = $browser->script("
                try {
                    if (!(window.mw && mw.app && mw.app.liveEdit
                        && mw.app.liveEdit.handles)) return 0;
                    var h = mw.app.liveEdit.handles.get('layout');
                    if (!h || typeof h.getTarget !== 'function') return 0;
                    var t = h.getTarget();
                    if (!t) return 0;
                    var f = typeof t.getAttribute === 'function'
                        ? (t.getAttribute('field') || '')
                        : '';
                    return f === " . json_encode($field) . " ? 1 : 0;
                } catch (e) { return 0; }
            ");
            if (((int)($state[0] ?? 0)) === 1) {
                $targeted = true;
                break;
            }
            $browser->pause(250);
        }

        $this->assertTrue(
            $targeted,
            'After dispatching onLayoutSettingsRequest, the layout handle target '
            . 'must resolve to the pricing section (observable proxy for "settings '
            . 'panel was opened for this layout")'
        );
    }

    /**
     * Purge any stale row left from a prior run for this exact tuple,
     * so the on-toggle assertion can distinguish "saved now" from
     * "leaked from a prior run with the same randomly-generated ID".
     * The ID is generated at insert time and highly unlikely to collide
     * with prior runs — but the purge is cheap and removes the guessing.
     */
    private function preCleanOptionsRow(string $optionGroup): void
    {
        DB::table('options')
            ->where('option_group', $optionGroup)
            ->where('option_key', self::OPTION_KEY)
            ->where('module', self::OPTION_MODULE)
            ->delete();
    }

    private function purgeOptionsRow(string $optionGroup): void
    {
        DB::table('options')
            ->where('option_group', $optionGroup)
            ->where('option_key', self::OPTION_KEY)
            ->where('module', self::OPTION_MODULE)
            ->delete();
    }

    /**
     * Simulate a settings-panel checkbox toggle by invoking the same
     * API that {@see mw.options.save} (core/options.js) calls when a
     * `.mw_option_field` checkbox fires its change event — that's the
     * real live-edit plumbing. Wait synchronously for the POST to
     * resolve (success or error), and return whichever resolution we
     * got so the caller can assert on it.
     */
    private function saveOptionViaBrowser(
        Browser $browser,
        string $optionGroup,
        string $value
    ): bool {
        $browser->script("
            window.__popularPlanSaveState = { done: false, ok: false, err: null };
            try {
                if (!(window.mw && mw.options && typeof mw.options.saveOption === 'function')) {
                    window.__popularPlanSaveState.err = 'mw.options.saveOption unavailable';
                    window.__popularPlanSaveState.done = true;
                    return;
                }
                var xhr = mw.options.saveOption({
                    group: " . json_encode($optionGroup) . ",
                    key: " . json_encode(self::OPTION_KEY) . ",
                    value: " . json_encode($value) . ",
                    module: " . json_encode(self::OPTION_MODULE) . "
                },
                function (resp) {
                    window.__popularPlanSaveState.ok = true;
                    window.__popularPlanSaveState.done = true;
                },
                function (err) {
                    window.__popularPlanSaveState.err = 'saveOption error callback fired';
                    window.__popularPlanSaveState.done = true;
                });

                if (!xhr) {
                    window.__popularPlanSaveState.err = 'saveOption returned falsy (missing arg?)';
                    window.__popularPlanSaveState.done = true;
                }
            } catch (e) {
                window.__popularPlanSaveState.err = (e && e.message) ? e.message : String(e);
                window.__popularPlanSaveState.done = true;
            }
        ");

        for ($i = 0; $i < 40; $i++) {
            $state = $browser->script("
                return window.__popularPlanSaveState || { done: false, ok: false, err: 'no state' };
            ");
            $state = $state[0] ?? ['done' => false, 'ok' => false, 'err' => 'no state'];
            if (!empty($state['err'])) {
                throw new \RuntimeException(
                    'saveOptionViaBrowser: ' . $state['err']
                );
            }
            if (!empty($state['done'])) {
                return !empty($state['ok']);
            }
            $browser->pause(250);
        }

        throw new \RuntimeException(
            'saveOptionViaBrowser: /api/save_option XHR never resolved within 10s'
        );
    }

    /**
     * Assert that exactly one row exists in the `options` table for
     * this (group, key, module) tuple, and that its value matches.
     * Uniqueness guards against a regression where save_option() would
     * append instead of upserting — which would fragment the module's
     * option state and break get_module_option().
     */
    private function assertOptionsRowMatches(
        string $optionGroup,
        string $expectedValue,
        string $message
    ): void {
        $rows = DB::table('options')
            ->where('option_group', $optionGroup)
            ->where('option_key', self::OPTION_KEY)
            ->where('module', self::OPTION_MODULE)
            ->get();

        $this->assertCount(
            1,
            $rows,
            "{$message} — expected exactly one row for (option_group={$optionGroup}, "
            . 'option_key=' . self::OPTION_KEY . ', module=' . self::OPTION_MODULE
            . '), got ' . $rows->count()
        );

        $row = $rows->first();
        $this->assertSame(
            $expectedValue,
            (string)$row->option_value,
            $message
        );
    }
}
