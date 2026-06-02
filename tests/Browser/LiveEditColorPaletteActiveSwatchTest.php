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
 * Phase-5 palette-picker active-swatch indicator.
 *
 * When the user clicks a color-palette swatch in the Template Settings
 * widget, {@see FieldStylePack.vue::applyStylePack()} sets
 * `activeStylePackIndex` to the clicked index and re-renders the iframe;
 * {@see createStylePackElement()} then adds `active` to the swatch whose
 * index matches. Sibling {@see LiveEditColorPaletteSwitchNoBleedTest}
 * proves the CSS side of an A→B switch; this test closes the UI-state
 * half — after A→B the picker must show exactly B as active, with A's
 * swatch having lost its indicator.
 *
 * Why this matters:
 *   - A regression that forgets to reassign `activeStylePackIndex` (or
 *     skips the follow-up `updateIframeContent()`) would leave both
 *     swatches un-highlighted, or stick on the old index. The user
 *     applies B but the picker still looks like A is selected — a sync
 *     bug invisible to `:root`-var-only tests.
 *
 * Post-click collapse behaviour:
 *   - In default (non-single-setting) mode, `applyStylePack` calls
 *     `collapseStylePacks()` right after recording the new index
 *     (FieldStylePack.vue:282-284). That hides every `.style-pack-item`
 *     from the picker iframe. To observe `.active`, the test re-expands
 *     the picker between each click and the snapshot — which is exactly
 *     what a user does when they revisit the picker.
 *
 * Harness:
 *   - Visits `/admin/live-edit?url=...` because the Vue picker is only
 *     mounted there (via `RightSidebar.vue` → `TemplateSettingsTeleport`
 *     → `mw.app.templateSettingsWidget`). The standalone
 *     `/admin/live-edit-template-settings-page` Filament page uses a
 *     *different* Livewire implementation that does not render
 *     `FieldStylePack`.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
#[Group('color-palettes')]
class LiveEditColorPaletteActiveSwatchTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    private const PALETTE_A = 'apple-shine';
    private const PALETTE_B = 'neon-night';
    private const LABEL_A = 'Apple Shine';
    private const LABEL_B = 'Neon Night';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function switching_palettes_moves_the_active_indicator(): void
    {
        $packs = array_column($this->listColorPalettes(), null, 'slug');
        $this->assertArrayHasKey(
            self::PALETTE_A,
            $packs,
            "palette A '" . self::PALETTE_A . "' must be discoverable on disk"
        );
        $this->assertArrayHasKey(
            self::PALETTE_B,
            $packs,
            "palette B '" . self::PALETTE_B . "' must be discoverable on disk"
        );
        $this->assertSame(
            self::LABEL_A,
            $packs[self::PALETTE_A]['title'],
            "pack '" . self::PALETTE_A . "' title must match the swatch label "
            . "the picker iframe renders (settings[0].title)"
        );
        $this->assertSame(
            self::LABEL_B,
            $packs[self::PALETTE_B]['title'],
            "pack '" . self::PALETTE_B . "' title must match the swatch label "
            . "the picker iframe renders (settings[0].title)"
        );

        $fixture = ColorPaletteFactory::make('active-swatch');

        $this->browse(function (Browser $browser) use ($fixture) {
            $this->loginAsAdmin($browser);

            // Drives `/admin/live-edit?url=...` and waits for
            // `mw.top().app.cssEditor` — the Vue TemplateSettings
            // component and its FieldStylePack children only mount in
            // this live-edit page.
            $this->openColorPaletteSidebar($browser, $fixture->pageId);

            $this->showTemplateSettingsWidget($browser);
            $this->navigateToPredefinedStyles($browser);

            $pickerIndex = $this->waitForColorPaletteIframe($browser);
            $this->ensurePickerExpanded($browser, $pickerIndex);
            $this->waitForSwatch($browser, $pickerIndex, self::LABEL_A);
            $this->waitForSwatch($browser, $pickerIndex, self::LABEL_B);

            // Click A; picker collapses; re-expand; snapshot active set.
            $this->clickSwatchByLabel($browser, $pickerIndex, self::LABEL_A);
            $browser->pause(400);
            $this->ensurePickerExpanded($browser, $pickerIndex);
            $activeAfterA = $this->snapshotActiveSwatchLabels($browser, $pickerIndex);

            $this->assertContains(
                self::LABEL_A,
                $activeAfterA,
                'After clicking A, its swatch must carry `.active`. Active now: '
                . json_encode($activeAfterA)
            );
            $this->assertNotContains(
                self::LABEL_B,
                $activeAfterA,
                "After clicking A, B's swatch must NOT be active. Active now: "
                . json_encode($activeAfterA)
            );

            // Click B; picker collapses; re-expand; snapshot active set.
            $this->clickSwatchByLabel($browser, $pickerIndex, self::LABEL_B);
            $browser->pause(400);
            $this->ensurePickerExpanded($browser, $pickerIndex);
            $activeAfterB = $this->snapshotActiveSwatchLabels($browser, $pickerIndex);

            $this->assertContains(
                self::LABEL_B,
                $activeAfterB,
                'After clicking B, its swatch must carry `.active`. Active now: '
                . json_encode($activeAfterB)
            );
            $this->assertNotContains(
                self::LABEL_A,
                $activeAfterB,
                "After clicking B, A's swatch must have lost `.active` "
                . "(switch-no-stale-indicator contract). Active now: "
                . json_encode($activeAfterB)
            );
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

    /**
     * Navigate the Vue TemplateSettings to the "Predefined Styles"
     * group. The root view renders each `mainStyleGroups` entry as an
     * `<a class="settings-main-group cursor-pointer" @click="navigateTo(...)">`;
     * a native click on the anchor triggers Vue's handler and flips
     * `currentPath` to `/predefined-styles`, which mounts the
     * FieldStylePack children.
     */
    private function navigateToPredefinedStyles(Browser $browser): void
    {
        for ($attempt = 0; $attempt < 40; $attempt++) {
            $state = $browser->script("
                try {
                    // If a FieldStylePack iframe is already mounted, we're there.
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
            'navigateToPredefinedStyles: no "Predefined Styles" nav link appeared '
            . 'within 20s of opening the template-settings widget'
        );
    }

    /**
     * Find the FieldStylePack iframe belonging to the "Website colors"
     * setting. The /predefined-styles group mounts several FieldStylePack
     * children (Full styles, Heading, Button, and Website colors), and
     * several of their openers include `.color-palette-item` previews —
     * so matching by preview markup alone picks the wrong iframe.
     *
     * Instead, locate the `<label class="live-edit-label">Website colors</label>`
     * rendered by the FieldStylePack template (line 3 of FieldStylePack.vue),
     * and walk to the sibling `.iframe-wrapper` → its `iframe.preview-iframe`.
     * Return that iframe's index in the top document's
     * `iframe.preview-iframe` NodeList — the same index every subsequent
     * helper uses to re-select the frame.
     */
    private function waitForColorPaletteIframe(Browser $browser): int
    {
        for ($attempt = 0; $attempt < 40; $attempt++) {
            $result = $browser->script("
                try {
                    var labels = document.querySelectorAll('label.live-edit-label');
                    var targetIframe = null;
                    for (var i = 0; i < labels.length; i++) {
                        var text = (labels[i].textContent || '').trim().toLowerCase();
                        if (text !== 'website colors') continue;

                        // FieldStylePack template renders the label div and
                        // the iframe-wrapper div as siblings. The iframe is
                        // the first preview-iframe within the wrapper that
                        // shares a common ancestor with the label.
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
                            // Also require the content document exists so
                            // callers can immediately probe the picker.
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
            'waitForColorPaletteIframe: no preview-iframe labelled "Website colors" '
            . 'became ready within 20s'
        );
    }

    /**
     * Ensure the FieldStylePack picker at `$pickerIndex` is in its
     * expanded state — `.style-pack-container.expanded` with every
     * `.style-pack-item` visible. If currently collapsed (showing only
     * the opener), click the opener to expand.
     */
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

    private function waitForSwatch(Browser $browser, int $pickerIndex, string $label): void
    {
        $labelJson = json_encode($label);
        for ($attempt = 0; $attempt < 30; $attempt++) {
            $result = $browser->script("
                try {
                    var frame = document.querySelectorAll('iframe.preview-iframe')[{$pickerIndex}];
                    if (!frame || !frame.contentDocument) return 0;
                    var doc = frame.contentDocument;
                    var labels = doc.querySelectorAll('.style-pack-item label.live-edit-label');
                    for (var i = 0; i < labels.length; i++) {
                        if ((labels[i].textContent || '').trim() === {$labelJson}) {
                            return 1;
                        }
                    }
                    return 0;
                } catch (e) {
                    return 0;
                }
            ");
            if ((int)($result[0] ?? 0) === 1) {
                return;
            }
            $browser->pause(500);
        }

        throw new \RuntimeException(
            "waitForSwatch: no swatch labelled '{$label}' appeared in the picker "
            . 'iframe within 15s'
        );
    }

    private function clickSwatchByLabel(Browser $browser, int $pickerIndex, string $label): void
    {
        $labelJson = json_encode($label);
        $result = $browser->script("
            try {
                var frame = document.querySelectorAll('iframe.preview-iframe')[{$pickerIndex}];
                if (!frame || !frame.contentDocument) return 'NO_FRAME';
                var doc = frame.contentDocument;
                var items = doc.querySelectorAll('.style-pack-item');
                for (var i = 0; i < items.length; i++) {
                    var lbl = items[i].querySelector('label.live-edit-label');
                    if (lbl && (lbl.textContent || '').trim() === {$labelJson}) {
                        items[i].click();
                        return 'OK:' + i;
                    }
                }
                return 'NOT_FOUND';
            } catch (e) {
                return 'ERR:' + (e && e.message ? e.message : e);
            }
        ");
        $outcome = (string)($result[0] ?? '');
        if (strpos($outcome, 'OK:') !== 0) {
            throw new \RuntimeException(
                "clickSwatchByLabel('{$label}'): {$outcome}"
            );
        }
    }

    /**
     * Return the label text of every `.style-pack-item.active` in the
     * picker iframe. We compare by label text (not index) to stay robust
     * against the opener re-rendering on each apply — label strings
     * survive re-render, but index positions in the DOM do too since the
     * pack JSON order is stable.
     *
     * @return array<int, string>
     */
    private function snapshotActiveSwatchLabels(Browser $browser, int $pickerIndex): array
    {
        $result = $browser->script("
            try {
                var frame = document.querySelectorAll('iframe.preview-iframe')[{$pickerIndex}];
                if (!frame || !frame.contentDocument) return [];
                var doc = frame.contentDocument;
                var actives = doc.querySelectorAll('.style-pack-item.active');
                var out = [];
                for (var i = 0; i < actives.length; i++) {
                    var lbl = actives[i].querySelector('label.live-edit-label');
                    if (lbl) {
                        out.push((lbl.textContent || '').trim());
                    } else {
                        out.push('<no-label>');
                    }
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
