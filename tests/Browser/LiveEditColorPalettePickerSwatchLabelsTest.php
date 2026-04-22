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
 * Phase-6 picker UX regression — swatch label parity.
 *
 * Asserts every swatch in the "Website colors" FieldStylePack picker
 * shows a visible label that matches the matching pack's
 * `settings[0].title` from the on-disk JSON.
 *
 * Why this is not redundant with {@see LiveEditColorPalettePickerSwatchCountTest}:
 *   The sibling test checks count and non-empty labels. This one checks
 *   the actual *text* of each label against the source of truth —
 *   catching regressions that the count test would miss:
 *   - A localization layer mutating pack titles on render (e.g. a
 *     translation pipeline stripping or truncating).
 *   - An HTML-escape regression that doubles entities or swallows
 *     special chars (the packs currently use plain ASCII, but a future
 *     pack with apostrophes or diacritics must round-trip cleanly).
 *   - A wrong-field mapping: FieldStylePack.vue reads
 *     `stylePack.label` (= `settings[0].fieldSettings.styleProperties[0].label`),
 *     and the packs today keep that in sync with `settings[0].title`.
 *     If a contributor adds a pack where the two diverge — or a merge
 *     adapter starts reading the wrong field — the picker would show a
 *     label the user doesn't expect; this test enforces `title === label`
 *     contract at the swatch level.
 *
 * Label source of truth:
 *   {@see LiveEditColorPaletteTrait::listColorPalettes()} already
 *   exposes `settings[0].title` as the `title` field. We rely on that
 *   so the test tracks the same canonical value the other palette tests
 *   treat as authoritative.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
#[Group('color-palettes')]
class LiveEditColorPalettePickerSwatchLabelsTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function every_swatch_label_matches_its_pack_json_title(): void
    {
        $packs = $this->listColorPalettes();
        $this->assertNotEmpty(
            $packs,
            'No color packs discoverable on disk — palette picker harness '
            . 'cannot assert label parity'
        );

        $expectedTitles = [];
        foreach ($packs as $pack) {
            $this->assertArrayHasKey(
                'title',
                $pack,
                "Pack '{$pack['slug']}' parsed without a title field — "
                . 'the JSON is missing `settings[0].title`'
            );
            $this->assertNotSame(
                '',
                trim((string)$pack['title']),
                "Pack '{$pack['slug']}' has an empty title — every pack "
                . 'must declare a non-empty `settings[0].title`'
            );
            $expectedTitles[] = (string)$pack['title'];
        }

        $fixture = ColorPaletteFactory::make('picker-swatch-labels');

        $this->browse(function (Browser $browser) use ($fixture, $expectedTitles) {
            $this->loginAsAdmin($browser);
            $this->openColorPaletteSidebar($browser, $fixture->pageId);

            $this->showTemplateSettingsWidget($browser);
            $this->navigateToPredefinedStyles($browser);

            $pickerIndex = $this->waitForWebsiteColorsIframe($browser);
            $this->ensurePickerExpanded($browser, $pickerIndex);

            $actualLabels = $this->snapshotSwatchLabels($browser, $pickerIndex);
            $this->assertNotEmpty(
                $actualLabels,
                'Website colors picker rendered zero labelled swatches — '
                . 'the picker body is empty'
            );

            // Order-independent comparison: the backend glob iterates
            // filesystem order, which is platform-dependent. What we
            // care about is set equality — every title in, every title
            // out, zero duplicates, zero extras.
            sort($expectedTitles);
            $normalized = array_map('trim', $actualLabels);
            sort($normalized);

            $this->assertSame(
                $expectedTitles,
                $normalized,
                'Website colors picker labels must match every pack JSON '
                . '`settings[0].title`. Expected (sorted): '
                . json_encode($expectedTitles)
                . ' | Got (sorted): ' . json_encode($normalized)
            );

            // Strict per-slug loop: for every on-disk pack, there is
            // exactly one rendered swatch with that title. Redundant
            // with the set-equality above, but its failure message
            // pinpoints the missing/duplicated slug by name, which is
            // more useful than a dumped array diff.
            $counts = array_count_values($normalized);
            foreach ($this->listColorPalettes() as $pack) {
                $title = (string)$pack['title'];
                $this->assertArrayHasKey(
                    $title,
                    $counts,
                    "Pack '{$pack['slug']}' (title '{$title}') is missing "
                    . 'from the picker — either the file was not globbed, '
                    . 'the title was mutated on render, or the swatch '
                    . 'label reads from a different field'
                );
                $this->assertSame(
                    1,
                    $counts[$title],
                    "Pack title '{$title}' appears {$counts[$title]} times "
                    . "in the picker for slug '{$pack['slug']}' — expected "
                    . 'exactly one swatch per pack'
                );
            }
        });
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
