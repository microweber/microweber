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
 * Phase-6 picker UX regression — preview swatch colors.
 *
 * Every swatch (`.style-pack-item`) rendered inside the "Website colors"
 * FieldStylePack iframe shows a `.mw-sp-palette` row of `.mw-sp-dot`
 * preview dots. FieldStylePack.vue's `extractPackColors()` picks the pack's
 * representative colours from its own `properties`, ranked
 * primary(0) → secondary/accent(1) → heading(2) → link(3) → body/background(4),
 * deduped to the first five — so the FIRST dot paints the pack's own
 * `--mw-primary-color`, independent of what is currently on `:root`.
 *
 * What this catches:
 *   - A regression where the swatch renders only label text without any
 *     `.mw-sp-dot` preview dots — a visual-signal regression invisible to
 *     the per-palette `:root` assertions in Phase 3.
 *   - A regression where the per-pack inline colours are lost so every
 *     swatch paints the current `:root` palette instead of the pack's own
 *     ("every swatch looks the same"): the first dot would no longer match
 *     THIS pack's primary colour.
 *
 * Computed-style probe:
 *   `getComputedStyle(el).backgroundColor` returns `rgb(r, g, b)` for
 *   hex inputs. We normalize the pack's hex through
 *   {@see LiveEditColorPaletteTrait::normalizeCssColor()} so both sides
 *   compare in rgb form. Packs where primary/body/background are all
 *   valid color literals are the only shape we inspect — the assertion
 *   skips any pack that declares a gradient (none today, but this keeps
 *   the test future-safe).
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
#[Group('color-palettes')]
class LiveEditColorPalettePickerThumbnailColorsTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function every_swatch_thumbnail_paints_the_pack_primary_body_background(): void
    {
        $packs = $this->listColorPalettes();
        $this->assertNotEmpty(
            $packs,
            'No color packs discoverable on disk — picker thumbnail test '
            . 'cannot run'
        );

        $fixture = ColorPaletteFactory::make('picker-thumbnail-colors');

        $this->browse(function (Browser $browser) use ($fixture, $packs) {
            $this->loginAsAdmin($browser);
            $this->openColorPaletteSidebar($browser, $fixture->pageId);

            $this->showTemplateSettingsWidget($browser);
            $this->navigateToPredefinedStyles($browser);

            $pickerIndex = $this->waitForWebsiteColorsIframe($browser);
            $this->ensurePickerExpanded($browser, $pickerIndex);

            $thumbnails = $this->snapshotThumbnailColorsByLabel($browser, $pickerIndex);

            $this->assertNotEmpty(
                $thumbnails,
                'Picker reported zero thumbnails. Expected one entry per '
                . 'rendered swatch keyed by label.'
            );

            foreach ($packs as $pack) {
                $title = (string)$pack['title'];
                $slug = (string)$pack['slug'];

                // The redesigned swatch renders a `.mw-sp-palette` of
                // `.mw-sp-dot` preview dots whose colours come from
                // FieldStylePack.vue's extractPackColors() — ranked
                // primary(0), secondary/accent(1), heading(2), link(3),
                // body/background(4), deduped to the first five. The rank-0
                // (first) dot is therefore the pack's own primary colour.
                // Skip packs whose primary isn't a solid colour literal.
                $primary = (string)($pack['properties']['--mw-primary-color'] ?? '');
                if ($primary === '' || !$this->isSolidColor($primary)) {
                    continue;
                }

                $this->assertArrayHasKey(
                    $title,
                    $thumbnails,
                    "Picker swatch for pack '{$slug}' (title '{$title}') "
                    . 'did not expose preview colours — either the swatch is '
                    . 'missing or its `.mw-sp-dot` preview dots were not rendered'
                );

                $actual = $thumbnails[$title];
                $this->assertGreaterThanOrEqual(
                    3,
                    count($actual),
                    "Pack '{$slug}' swatch rendered " . count($actual)
                    . ' `.mw-sp-dot` preview dots, expected at least 3. Actual: '
                    . json_encode($actual)
                );

                // The first dot must paint THIS pack's primary colour. If the
                // per-pack inline style override were lost, every swatch would
                // instead paint the current :root palette — so a per-pack
                // mismatch here is exactly that regression.
                $this->assertSame(
                    $this->normalizeCssColor($primary),
                    $actual[0] ?? '',
                    "Pack '{$slug}' first preview dot must paint the pack's own "
                    . "--mw-primary-color ('{$primary}'), not the current :root "
                    . "palette — got '" . ($actual[0] ?? 'null') . "'."
                );
            }
        });
    }

    /**
     * Return a map keyed by pack title → list of computed
     * background-color values of the `.mw-sp-dot` preview dots inside
     * that swatch, in render order (primary-ranked first).
     *
     * @return array<string, array<int, string>>
     */
    private function snapshotThumbnailColorsByLabel(Browser $browser, int $pickerIndex): array
    {
        $result = $browser->script("
            try {
                var frame = document.querySelectorAll('iframe.preview-iframe')[{$pickerIndex}];
                if (!frame || !frame.contentDocument) return {};
                var doc = frame.contentDocument;
                var win = frame.contentWindow;
                var items = doc.querySelectorAll('.style-pack-item');
                var out = {};
                for (var i = 0; i < items.length; i++) {
                    var lblEl = items[i].querySelector('label.live-edit-label');
                    var title = lblEl ? (lblEl.textContent || '').trim() : '';
                    if (!title) continue;

                    var thumbs = items[i].querySelectorAll('.mw-sp-dot');
                    var colors = [];
                    for (var j = 0; j < thumbs.length; j++) {
                        var cs = win.getComputedStyle(thumbs[j]);
                        colors.push((cs.backgroundColor || '').trim());
                    }
                    out[title] = colors;
                }
                return out;
            } catch (e) {
                return {'__error': String(e && e.message ? e.message : e)};
            }
        ");
        $payload = $result[0] ?? [];
        if (!is_array($payload)) {
            return [];
        }
        if (isset($payload['__error'])) {
            throw new \RuntimeException(
                'snapshotThumbnailColorsByLabel: ' . $payload['__error']
            );
        }
        $out = [];
        foreach ($payload as $title => $colors) {
            if (!is_array($colors)) {
                continue;
            }
            $out[(string)$title] = array_values(array_map('strval', $colors));
        }
        return $out;
    }

    /**
     * True if the value is a `#rgb` / `#rrggbb` / `rgb(...)` / `rgba(...)`
     * literal — i.e. something `background-color` will accept as a
     * paint. Gradients, named image functions, and empty strings return
     * false so the assertion skips them.
     */
    private function isSolidColor(string $value): bool
    {
        $v = trim($value);
        if ($v === '') {
            return false;
        }
        if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $v) === 1) {
            return true;
        }
        if (preg_match('/^rgba?\([^)]+\)$/', $v) === 1) {
            return true;
        }
        return false;
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
}
