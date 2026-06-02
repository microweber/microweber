<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\ColorPaletteFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansColorPaletteTestFixtures;
use Tests\Browser\Traits\LiveEditColorPaletteTrait;
use Tests\DuskTestCase;

/**
 * Phase-6 picker UX regression — swatch count.
 *
 * The "Website colors" FieldStylePack picker must render exactly one
 * `.style-pack-item` per JSON file under
 * `Templates/Bootstrap/resources/assets/design-styles/style-packs/colors/`.
 *
 * Binding path exercised:
 *   - `main-colors.json` declares `mergeFieldSettingsFromFolders` pointing
 *     at the style-packs/colors folder.
 *   - The backend glob resolves every `*.json` there and flattens them
 *     into `setting.fieldSettings.styleProperties[]`.
 *   - FieldStylePack.vue's `createStylePackElement()` appends one
 *     `.style-pack-item` per entry when the picker expands.
 *
 * Regressions this catches:
 *   - A glob-expansion bug (wrong base dir, case-sensitive match) that
 *     silently drops packs → count < 17 on disk count.
 *   - A style-pack de-dupe bug (accidental array_values on an assoc
 *     array, merge conflict dropping an entry) → count < disk count.
 *   - A hidden-swatch CSS regression that removes items from the layout
 *     but leaves them in the DOM is NOT what we want to catch here; we
 *     explicitly count DOM elements (not visibility) so this test is
 *     *orthogonal* to visual CSS regressions — a blank-swatch regression
 *     is caught by the per-palette render tests in Phase 3.
 *
 * Source of truth:
 *   - The on-disk file count (17 today) — computed at runtime. If packs
 *     are added or removed, the assertion tracks automatically, which is
 *     better than a hardcoded constant that would silently pass on a new
 *     pack that fails to render.
 *
 * Harness reuses the ActiveSwatch test's navigation path: visit
 * `/admin/live-edit?url=...` → programmatically show
 * `mw.top().app.templateSettingsWidget` → navigate the Vue
 * TemplateSettings to "Predefined Styles" → locate the FieldStylePack
 * iframe whose parent label is "Website colors" → expand it.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
#[Group('color-palettes')]
class LiveEditColorPalettePickerSwatchCountTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function website_colors_picker_shows_one_swatch_per_pack_file(): void
    {
        $packs = $this->listColorPalettes();
        $expectedCount = count($packs);
        $this->assertGreaterThan(
            0,
            $expectedCount,
            'At least one color pack must be discoverable on disk — the '
            . 'Bootstrap template-packs folder appears to be empty'
        );

        $fixture = ColorPaletteFactory::make('picker-swatch-count');

        $this->browse(function (Browser $browser) use ($fixture, $expectedCount, $packs) {
            $this->loginAsAdmin($browser);
            $this->openColorPaletteSidebar($browser, $fixture->pageId);

            $this->showTemplateSettingsWidget($browser);
            $this->navigateToPredefinedStyles($browser);

            $pickerIndex = $this->waitForWebsiteColorsIframe($browser);
            $this->ensurePickerExpanded($browser, $pickerIndex);

            $actualCount = $this->countSwatches($browser, $pickerIndex);

            $this->assertSame(
                $expectedCount,
                $actualCount,
                "Website colors picker must render exactly {$expectedCount} "
                . "swatches (one per JSON file in style-packs/colors/), "
                . "but rendered {$actualCount}. Disk packs: "
                . implode(', ', array_column($packs, 'slug'))
            );

            // Cross-check labels are non-empty so a regression that
            // emits the right number of div shells with missing labels
            // also fails here.
            $labels = $this->snapshotSwatchLabels($browser, $pickerIndex);
            $this->assertCount(
                $expectedCount,
                $labels,
                "Website colors picker: expected {$expectedCount} labelled "
                . 'swatches, got ' . count($labels) . '. Labels: '
                . json_encode($labels)
            );
            foreach ($labels as $i => $text) {
                $this->assertNotSame(
                    '',
                    trim($text),
                    "Swatch #{$i} in Website colors picker has an empty label — "
                    . 'FieldStylePack rendered a shell without its title'
                );
            }
        });
    }

    /**
     * Programmatically open the template-settings panel by dispatching
     * the `mw.open-template-settings` event on `mw.top().app`. This
     * triggers `SettingsCustomize.vue`'s handler which calls
     * `show('template-settings')`, emitting the mitt `live-edit-ui-show`
     * event so `RightSidebar.vue` sets `showSidebar = true` and
     * `TemplateSettingsTeleport` can mount. Without the mitt emit,
     * the controlBox container is visible but the Vue teleport body
     * stays empty (task-2026-05-29-eaf3a1 / AI-1157 fix chain).
     */
    private function showTemplateSettingsWidget(Browser $browser): void
    {
        for ($attempt = 0; $attempt < 30; $attempt++) {
            $state = $browser->script("
                try {
                    var app = window.mw && mw.top && mw.top().app;
                    if (!app) return 'NO_APP';
                    var w = app.templateSettingsWidget;
                    if (!w) return 'NO_WIDGET';
                    // Dispatch the named event so SettingsCustomize.vue's handler
                    // calls show('template-settings'), which emits live-edit-ui-show
                    // via the Vue mitt bus — this sets showSidebar = true in
                    // RightSidebar.vue so TemplateSettingsTeleport mounts.
                    if (typeof app.dispatch === 'function') {
                        app.dispatch('mw.open-template-settings');
                    }
                    // Also call show() directly as belt-and-suspenders in case
                    // the SettingsCustomize handler is not yet registered.
                    if (typeof w.show === 'function') {
                        w.show();
                    }
                    return 'SHOWN';
                } catch (e) {
                    return 'ERR:' + (e && e.message ? e.message : e);
                }
            ");
            $outcome = (string)($state[0] ?? '');
            if ($outcome === 'SHOWN') {
                $browser->pause(2500);
                return;
            }
            $browser->pause(500);
        }

        throw new \RuntimeException(
            'showTemplateSettingsWidget: mw.top().app.templateSettingsWidget never '
            . 'became available within 15s'
        );
    }

    private function navigateToPredefinedStyles(Browser $browser): void
    {
        for ($attempt = 0; $attempt < 40; $attempt++) {
            $state = $browser->script("
                try {
                    if (document.querySelector('iframe.preview-iframe')) {
                        return 'ALREADY_ON_PAGE';
                    }
                    var anchors = document.querySelectorAll('a.settings-main-group, .settings-main-group');
                    for (var i = 0; i < anchors.length; i++) {
                        var el = anchors[i];
                        var txt = (el.textContent || '').trim().toLowerCase();
                        if (txt === 'predefined styles') {
                            el.click();
                            return 'CLICKED';
                        }
                    }
                    return 'NOT_FOUND';
                } catch (e) {
                    return 'ERR:' + (e && e.message ? e.message : e);
                }
            ");
            $outcome = (string)($state[0] ?? '');
            if ($outcome === 'ALREADY_ON_PAGE') {
                return;
            }
            if ($outcome === 'CLICKED') {
                $browser->pause(2000);
                return;
            }
            $browser->pause(500);
        }

        throw new \RuntimeException(
            'navigateToPredefinedStyles: no "Predefined Styles" nav link '
            . 'appeared within 20s'
        );
    }

    /**
     * Locate the FieldStylePack iframe whose parent wrapper was labelled
     * "Website colors" by the FieldStylePack Vue template. Same strategy
     * as LiveEditColorPaletteActiveSwatchTest, kept local to avoid
     * widening the shared trait with a scenario-specific helper.
     */
    private function waitForWebsiteColorsIframe(Browser $browser): int
    {
        for ($attempt = 0; $attempt < 40; $attempt++) {
            $result = $browser->script("
                try {
                    var labels = document.querySelectorAll('label.live-edit-label');
                    var targetIframe = null;
                    for (var i = 0; i < labels.length; i++) {
                        var text = (labels[i].textContent || '').trim().toLowerCase();
                        if (text !== 'website colors') continue;
                        var labelRoot = labels[i].closest('div');
                        if (!labelRoot) continue;
                        var next = labelRoot.nextElementSibling;
                        while (next) {
                            var frame = next.querySelector
                                ? next.querySelector('iframe.preview-iframe')
                                : null;
                            if (frame) { targetIframe = frame; break; }
                            next = next.nextElementSibling;
                        }
                        if (targetIframe) break;
                    }
                    if (!targetIframe) return -1;

                    var allFrames = document.querySelectorAll('iframe.preview-iframe');
                    for (var k = 0; k < allFrames.length; k++) {
                        if (allFrames[k] === targetIframe) {
                            try {
                                if (targetIframe.contentDocument) return k;
                            } catch (_) {}
                            return -1;
                        }
                    }
                    return -1;
                } catch (e) {
                    return -1;
                }
            ");
            $index = (int)($result[0] ?? -1);
            if ($index >= 0) {
                return $index;
            }
            $browser->pause(500);
        }

        throw new \RuntimeException(
            'waitForWebsiteColorsIframe: no preview-iframe labelled '
            . '"Website colors" became ready within 20s'
        );
    }

    private function ensurePickerExpanded(Browser $browser, int $pickerIndex): void
    {
        for ($attempt = 0; $attempt < 30; $attempt++) {
            $state = $browser->script("
                try {
                    var frame = document.querySelectorAll('iframe.preview-iframe')[{$pickerIndex}];
                    if (!frame || !frame.contentDocument) return 'NO_FRAME';
                    var doc = frame.contentDocument;

                    var container = doc.querySelector('.style-pack-container');
                    var expanded = container && container.classList.contains('expanded');
                    var visibleSwatches = doc.querySelectorAll('.style-pack-item').length;

                    if (expanded && visibleSwatches > 1) {
                        return 'EXPANDED:' + visibleSwatches;
                    }
                    var opener = doc.querySelector('.style-pack-opener');
                    if (opener) {
                        opener.click();
                        return 'CLICKED_OPENER';
                    }
                    return 'NO_OPENER';
                } catch (e) {
                    return 'ERR:' + (e && e.message ? e.message : e);
                }
            ");
            $outcome = (string)($state[0] ?? '');
            if (strpos($outcome, 'EXPANDED:') === 0) {
                return;
            }
            $browser->pause(500);
        }

        throw new \RuntimeException(
            'ensurePickerExpanded: picker never reached the expanded-with-swatches '
            . 'state within 15s'
        );
    }

    private function countSwatches(Browser $browser, int $pickerIndex): int
    {
        $result = $browser->script("
            try {
                var frame = document.querySelectorAll('iframe.preview-iframe')[{$pickerIndex}];
                if (!frame || !frame.contentDocument) return -1;
                return frame.contentDocument.querySelectorAll('.style-pack-item').length;
            } catch (e) {
                return -1;
            }
        ");
        return (int)($result[0] ?? -1);
    }

    /**
     * @return array<int, string>
     */
    private function snapshotSwatchLabels(Browser $browser, int $pickerIndex): array
    {
        $result = $browser->script("
            try {
                var frame = document.querySelectorAll('iframe.preview-iframe')[{$pickerIndex}];
                if (!frame || !frame.contentDocument) return [];
                var doc = frame.contentDocument;
                var items = doc.querySelectorAll('.style-pack-item');
                var out = [];
                for (var i = 0; i < items.length; i++) {
                    var lbl = items[i].querySelector('label.live-edit-label');
                    out.push(lbl ? (lbl.textContent || '').trim() : '');
                }
                return out;
            } catch (e) {
                return [];
            }
        ");
        $payload = $result[0] ?? [];
        return is_array($payload) ? array_values(array_map('strval', $payload)) : [];
    }
}
