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
 * Phase-4 background-module regression: the jumbotron hero's overlay
 * color picker must set the `.mw-layout-background-overlay` element's
 * inline `background-color` style AND stage a `data-background-color`
 * option against the nested background module. After Save, the option
 * must land in the `options` table so the public render re-emits the
 * same inline style on the overlay div.
 *
 * Task framing (TODO.md Phase 4):
 *   "Use background module color picker on jumbotron hero; assert
 *    `background-color` inline style saved".
 *
 * Why this path:
 *   - jumbotron/skin-1.blade.php declares an inline
 *     `<module type="background" id="background-layout--<id>" …/>` (see
 *     Templates/Bootstrap/resources/views/modules/layouts/templates/
 *     jumbotron/skin-1.blade.php:30). On render, the BackgroundModule
 *     outputs `.mw-layout-background-overlay` whose inline
 *     `background-color` is bound to the `data-background-color`
 *     option of that specific module id.
 *   - The live-edit color picker in the Layouts module settings panel
 *     (public/modules/layouts/js/layouts-module-settings.js:234)
 *     invokes `mw.top().app.layoutBackground.setBackgroundColor(
 *     bgOverlay, color)` — that one call both sets the overlay's inline
 *     style AND calls `mw.options.tempOption(...)` on the nested
 *     background module (live-edit-layout-background.js:276-311).
 *   - At Save time, `publishTempOptions(document)` (fired from
 *     live-edit-page-scripts.js:289) flushes each staged temp option
 *     through `mw.options.saveOption()` → POST /api/save_option →
 *     `save_option()` → `options` table row keyed by (option_group,
 *     option_key='data-background-color', module='background'). On the
 *     next render, default.blade.php echoes `background-color: {{
 *     $background_color }}` into the overlay's inline `style` attr.
 *
 * Flow:
 *   1. Seed a Bootstrap landing page (clean layout).
 *   2. Open in live-edit; insert jumbotron/skin-1.
 *   3. Locate the jumbotron section and the background module inside
 *      it; capture the module's id (the `option_group`).
 *   4. Call `mw.app.layoutBackground.setBackgroundColor(overlay, color)`
 *      — the production picker path — then assert:
 *        a. the overlay's inline `style.backgroundColor` reflects the
 *           color we picked (normalized to rgb(...) form by the DOM);
 *        b. the tempOption was staged on the background module
 *           (`data-mw-temp-option-save` contains our tuple).
 *   5. Save the live-edit page; wait for the save XHR.
 *   6. Assert the `options` table has exactly one row for
 *      (option_group=<bgModuleId>, option_key='data-background-color',
 *      module='background') and option_value equals the color string.
 *   7. Visit the public URL; assert the rendered HTML contains
 *      `style="background-color: <color>;"` on the overlay element —
 *      the round-trip "inline style saved" proof.
 *   8. Purge the options row so the run leaves no leaked state beyond
 *      the landing-test page (handled by CleansLandingTestPages).
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditJumbotronBackgroundColorInlineStyleTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    private const OPTION_KEY = 'data-background-color';
    private const OPTION_MODULE = 'background';

    /**
     * A vivid, non-default color in canonical `rgb(R, G, B)` form.
     * The DOM normalizes any color string passed to `style.backgroundColor`
     * to this exact shape, so using it as input keeps inline-style and
     * saved-option comparisons character-for-character stable.
     */
    private const PICKED_COLOR = 'rgb(12, 205, 60)';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function jumbotron_background_color_picker_persists_inline_style_through_save(): void
    {
        $landing = LandingPageFactory::make('Jumbotron background color picker');

        $this->browse(function (Browser $browser) use ($landing) {
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            $this->primeLayoutHandleOnMainContent($browser);
            $this->insertLayoutByCategory($browser, 'Jumbotron', 'jumbotron/skin-1');

            $sectionField = $this->waitForJumbotronSection($browser);
            $bgContext = $this->resolveBackgroundContext($browser, $sectionField);

            $this->assertNotSame(
                '',
                $bgContext['moduleId'],
                "Jumbotron section '{$sectionField}' must carry a nested background "
                . 'module with a resolvable id — otherwise saveOption has no option_group'
            );

            $this->preCleanOptionsRow($bgContext['moduleId']);

            $invocation = $this->invokeBackgroundColorPicker($browser, $sectionField);
            $this->assertSame(
                'OK',
                $invocation['status'],
                'mw.app.layoutBackground.setBackgroundColor must run without error: '
                . ($invocation['reason'] ?? '')
            );
            $this->assertSame(
                self::PICKED_COLOR,
                $invocation['inlineBackgroundColor'],
                'After the color picker, the overlay\'s inline style.backgroundColor '
                . 'must equal the picked color — this is the live-edit-side contract '
                . 'the test title names "background-color inline style saved"'
            );
            $this->assertTrue(
                $invocation['tempOptionStaged'],
                'The color picker must also stage a data-background-color temp option '
                . 'on the background module, so Save\'s publishTempOptions flushes it '
                . 'to the options table'
            );

            $this->saveLiveEdit($browser);
            $browser->pause(1000);

            $this->assertOptionsRowMatches(
                $bgContext['moduleId'],
                self::PICKED_COLOR,
                'After Save, the options table must carry exactly one row for '
                . "(option_group={$bgContext['moduleId']}, option_key="
                . self::OPTION_KEY . ', module=' . self::OPTION_MODULE
                . ') with option_value equal to the picked color'
            );

            $this->assertPublicPageContains(
                $browser,
                $landing->slug,
                'background-color: ' . self::PICKED_COLOR
            );

            $this->purgeOptionsRow($bgContext['moduleId']);
        });
    }

    /**
     * Set mw.app.liveEdit.handles.get('layout') target to the page's
     * `.section.edit.main-content`. Without a primed target, the Insert
     * Layout picker falls back to whichever section was last hovered,
     * which is flaky for a test that inserts on a blank page.
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

    private function waitForJumbotronSection(Browser $browser): string
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                var sec = doc.querySelector('section.section.edit[field^=\"layout-jumbotron-skin-1-\"]');
                if (!sec) return '';
                return sec.getAttribute('field') || '';
            ");
            $fieldAttr = (string)($res[0] ?? '');
            if ($fieldAttr !== '') {
                $browser->pause(800);
                return $fieldAttr;
            }
            $browser->pause(500);
        }
        $browser->screenshot('fail-jumbotron-bg-color-section-missing');
        throw new \RuntimeException('Jumbotron section never appeared in the canvas within 15s');
    }

    /**
     * Find the `.mw-layout-background-overlay` inside the jumbotron
     * section, walk up to the nearest background-module wrapper, and
     * return both the module id (future option_group) and the module
     * type string — so the caller can sanity-check that the traversal
     * actually hit a `type="background"` module and not some outer
     * non-background ancestor.
     *
     * @return array{moduleId:string, moduleType:string, overlayFound:bool}
     */
    private function resolveBackgroundContext(Browser $browser, string $sectionField): array
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var sel = 'section[field=' + JSON.stringify(" . json_encode($sectionField) . ") + ']';
            var section = doc.querySelector(sel);
            if (!section) return { moduleId: '', moduleType: '', overlayFound: false };

            var overlay = section.querySelector('.mw-layout-background-overlay');
            if (!overlay) return { moduleId: '', moduleType: '', overlayFound: false };

            var node = overlay;
            while (node && node !== section) {
                var tAttr = (node.getAttribute && (node.getAttribute('type')
                    || node.getAttribute('data-type'))) || '';
                if (tAttr === 'background') {
                    return {
                        moduleId: node.id || '',
                        moduleType: tAttr,
                        overlayFound: true
                    };
                }
                node = node.parentElement;
            }
            return { moduleId: '', moduleType: '', overlayFound: true };
        ");

        $payload = $res[0] ?? [];
        return [
            'moduleId' => (string)($payload['moduleId'] ?? ''),
            'moduleType' => (string)($payload['moduleType'] ?? ''),
            'overlayFound' => (bool)($payload['overlayFound'] ?? false),
        ];
    }

    /**
     * Call the real production color-picker entry point and capture the
     * observable side-effects in one script trip.
     *
     * Why we go through `mw.app.layoutBackground.setBackgroundColor`
     * rather than just poking `style.backgroundColor`: that function is
     * what the Layouts Alpine settings panel wires into the inline
     * color picker's `onchange` (see
     * Modules/Layouts/resources/assets/js/layouts-module-settings.js:234).
     * Hitting the same entry point guarantees the test also exercises
     * the tempOption staging on the background module, which is what
     * `publishTempOptions` flushes at save time.
     *
     * @return array{
     *   status: string,
     *   reason: string|null,
     *   inlineBackgroundColor: string,
     *   tempOptionStaged: bool
     * }
     */
    private function invokeBackgroundColorPicker(Browser $browser, string $sectionField): array
    {
        $res = $browser->script("
            try {
                var doc = mw.app.canvas.getDocument();
                var sel = 'section[field=' + JSON.stringify(" . json_encode($sectionField) . ") + ']';
                var section = doc.querySelector(sel);
                if (!section) return { status: 'NO_SECTION' };

                var overlay = section.querySelector('.mw-layout-background-overlay');
                if (!overlay) return { status: 'NO_OVERLAY' };

                if (!(window.mw && mw.app && mw.app.layoutBackground
                    && typeof mw.app.layoutBackground.setBackgroundColor === 'function')) {
                    return { status: 'NO_LAYOUT_BACKGROUND_API' };
                }

                mw.app.layoutBackground.setBackgroundColor(overlay, " . json_encode(self::PICKED_COLOR) . ");

                // Read the staged temp-option payload off the module node
                var bgModule = overlay.closest('[type=\"background\"], [data-type=\"background\"]');
                var tempAttr = bgModule ? (bgModule.getAttribute('data-mw-temp-option-save') || '') : '';
                var staged = false;
                if (tempAttr) {
                    try {
                        var parsed = JSON.parse(tempAttr);
                        if (Array.isArray(parsed)) {
                            for (var i = 0; i < parsed.length; i++) {
                                if (parsed[i]
                                    && parsed[i].key === " . json_encode(self::OPTION_KEY) . "
                                    && parsed[i].module === " . json_encode(self::OPTION_MODULE) . "
                                    && String(parsed[i].value) === " . json_encode(self::PICKED_COLOR) . ") {
                                    staged = true;
                                    break;
                                }
                            }
                        }
                    } catch (e) {}
                }

                return {
                    status: 'OK',
                    reason: null,
                    inlineBackgroundColor: overlay.style.backgroundColor || '',
                    tempOptionStaged: staged
                };
            } catch (e) {
                return {
                    status: 'ERROR',
                    reason: (e && e.message) ? e.message : String(e),
                    inlineBackgroundColor: '',
                    tempOptionStaged: false
                };
            }
        ");

        $payload = $res[0] ?? [];
        return [
            'status' => (string)($payload['status'] ?? 'UNKNOWN'),
            'reason' => isset($payload['reason']) ? (string)$payload['reason'] : null,
            'inlineBackgroundColor' => (string)($payload['inlineBackgroundColor'] ?? ''),
            'tempOptionStaged' => (bool)($payload['tempOptionStaged'] ?? false),
        ];
    }

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

        $this->assertSame(
            $expectedValue,
            (string)$rows->first()->option_value,
            $message
        );
    }
}
