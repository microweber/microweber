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
 * Phase-6 picker UX regression — preview thumbnail colors.
 *
 * Every swatch (`.style-pack-item`) rendered inside the "Website colors"
 * FieldStylePack iframe contains three `.color-palette-item`
 * preview thumbnails, declared by `main-colors.json`:
 *
 *   previewElements[0] → background-color: var(--mw-primary-color)
 *   previewElements[1] → background-color: var(--mw-body-color)
 *   previewElements[2] → background-color: var(--mw-background-color)
 *
 * FieldStylePack.vue's `createStylePackElement()` (line 852) sets the
 * pack's CSS custom properties as inline style on both the swatch `div`
 * and every preview `component`. The preview's `background-color: var(...)`
 * attribute resolves against those inline custom props — so each
 * thumbnail ends up painted with the pack's own color, independent of
 * what's currently on `:root`.
 *
 * What this catches:
 *   - A regression where `main-colors.json`'s `previewElements` shape
 *     (`colorPaletteFromTemplateFolderLibrary`-style thumbnails) is
 *     dropped or replaced by the fallback-selectors branch at
 *     FieldStylePack.vue:917. The swatch would render only label text
 *     without colored thumbnails — a visual-signal regression invisible
 *     to the per-palette `:root` assertions in Phase 3.
 *   - A regression where the per-pack inline style override is lost
 *     (e.g. only `stylePackDiv.style.setProperty` is called but
 *     `component.style.setProperty` is dropped, or the preview element
 *     doesn't inherit the custom props from an ancestor). All three
 *     thumbnails would then paint the current `:root` palette instead
 *     of the pack's own palette — a "every swatch looks the same" bug.
 *   - A regression where the preview-element ordering mutates; the
 *     test asserts each index paints the expected variable, so swaps
 *     surface as per-index mismatches.
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

    /**
     * Preview-element order declared by `main-colors.json` —
     * the FieldStylePack renderer iterates `previewElements[]`
     * in-order and appends `.color-palette-item` children in that
     * order. Keep this array aligned with the pack JSON.
     *
     * @var list<string>
     */
    private const THUMBNAIL_VAR_ORDER = [
        '--mw-primary-color',
        '--mw-body-color',
        '--mw-background-color',
    ];

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

                // Skip packs that declare any of the three core vars in
                // a non-color form — `background-color` rejects gradients
                // and the thumbnail would fall back to transparent.
                $skip = false;
                $expected = [];
                foreach (self::THUMBNAIL_VAR_ORDER as $var) {
                    $val = (string)($pack['properties'][$var] ?? '');
                    if ($val === '' || !$this->isSolidColor($val)) {
                        $skip = true;
                        break;
                    }
                    $expected[] = $this->normalizeCssColor($val);
                }
                if ($skip) {
                    continue;
                }

                $this->assertArrayHasKey(
                    $title,
                    $thumbnails,
                    "Picker swatch for pack '{$slug}' (title '{$title}') "
                    . 'did not expose thumbnail colors — either the swatch '
                    . 'is missing or its preview `.color-palette-item` '
                    . 'children were not rendered'
                );

                $actual = $thumbnails[$title];
                $this->assertCount(
                    count(self::THUMBNAIL_VAR_ORDER),
                    $actual,
                    "Pack '{$slug}' swatch rendered " . count($actual)
                    . ' `.color-palette-item` thumbnails, expected '
                    . count(self::THUMBNAIL_VAR_ORDER) . '. Actual: '
                    . json_encode($actual)
                );

                foreach (self::THUMBNAIL_VAR_ORDER as $i => $var) {
                    $this->assertSame(
                        $expected[$i],
                        $actual[$i],
                        "Pack '{$slug}' thumbnail #{$i} (bound to {$var}) "
                        . "expected '{$expected[$i]}' but got '{$actual[$i]}'. "
                        . 'This means the per-pack preview did not override '
                        . 'the inline CSS variable — thumbnails are painting '
                        . 'the current :root palette instead of the packs.'
                    );
                }
            }
        });
    }

    /**
     * Return a map keyed by pack title → list of computed
     * `background-color` values of the three `.color-palette-item`
     * thumbnails inside that swatch, in render order.
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

                    var thumbs = items[i].querySelectorAll('.color-palette-item');
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

    private function showTemplateSettingsWidget(Browser $browser): void
    {
        for ($attempt = 0; $attempt < 30; $attempt++) {
            $state = $browser->script("
                try {
                    var w = window.mw && mw.top && mw.top().app
                        && mw.top().app.templateSettingsWidget;
                    if (!w) return 'NO_WIDGET';
                    if (typeof w.show === 'function') {
                        w.show();
                        return 'SHOWN';
                    }
                    return 'NO_SHOW_FN';
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
            'showTemplateSettingsWidget: mw.top().app.templateSettingsWidget '
            . 'never became available within 15s'
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
