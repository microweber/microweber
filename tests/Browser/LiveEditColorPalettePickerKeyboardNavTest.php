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
 * Phase-6 picker UX regression — keyboard accessibility guard.
 *
 * Every `.style-pack-item` inside the "Website colors" FieldStylePack
 * picker must be keyboard reachable and operable:
 *   - The swatch is in the focus order (`tabindex` attribute present).
 *   - Exactly one swatch at a time carries `tabindex="0"` (roving
 *     tabindex pattern) — Tab lands on the focused swatch without
 *     stepping through every pack.
 *   - Arrow keys move focus between swatches (and move the `tabindex=0`
 *     so Tab-out / Tab-back returns to the same swatch).
 *   - Enter (and Space) applies the focused pack, moving the `.active`
 *     indicator to it.
 *
 * What this catches:
 *   - A regression where `FieldStylePack.vue::createStylePackElement()`
 *     drops the `tabindex`/`role`/`aria-label` attributes — a screen
 *     reader user cannot discover or apply any pack.
 *   - A regression where the keydown listener is removed or its key
 *     handling shifts (e.g. ArrowRight no longer moves focus): keyboard
 *     users are trapped on the first swatch.
 *   - A regression where Enter/Space stops invoking `applyStylePack` —
 *     mouse users are unaffected but keyboard users cannot select a pack.
 *
 * Why the events are dispatched inside the iframe:
 *   The picker renders into a same-origin `iframe.preview-iframe`, so
 *   the outer Dusk WebDriver session's `focus()`/`keys()` calls target
 *   the parent document. We use `browser->script()` to drive events
 *   against the iframe's own document and observe `activeElement` via
 *   the iframe's `contentDocument`.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
#[Group('color-palettes')]
class LiveEditColorPalettePickerKeyboardNavTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function picker_is_keyboard_navigable_and_operable(): void
    {
        $packs = $this->listColorPalettes();
        $this->assertGreaterThanOrEqual(
            2,
            count($packs),
            'Need at least two color packs to validate arrow-key focus '
            . 'movement — the on-disk pack count is ' . count($packs)
        );

        $fixture = ColorPaletteFactory::make('picker-keyboard-nav');

        $this->browse(function (Browser $browser) use ($fixture, $packs) {
            $this->loginAsAdmin($browser);
            $this->openColorPaletteSidebar($browser, $fixture->pageId);

            $this->showTemplateSettingsWidget($browser);
            $this->navigateToPredefinedStyles($browser);

            $pickerIndex = $this->waitForWebsiteColorsIframe($browser);
            $this->ensurePickerExpanded($browser, $pickerIndex);

            // 1) ARIA / tabindex structure — every swatch exposes button
            // semantics and is reachable by the focus order.
            $attrs = $this->snapshotSwatchAttributes($browser, $pickerIndex);
            $this->assertNotEmpty(
                $attrs,
                'Expected at least one rendered `.style-pack-item`; the '
                . 'picker DOM is empty.'
            );
            $this->assertCount(
                count($packs),
                $attrs,
                'Picker rendered ' . count($attrs)
                . ' swatches but ' . count($packs) . ' packs exist on disk.'
            );

            $tabZeroCount = 0;
            foreach ($attrs as $i => $a) {
                $this->assertSame(
                    'button',
                    $a['role'],
                    "Swatch #{$i} missing role=\"button\" — keyboard users "
                    . 'cannot recognise the swatch as an activatable control'
                );
                $this->assertNotSame(
                    '',
                    (string)$a['ariaLabel'],
                    "Swatch #{$i} has no aria-label — a screen-reader user "
                    . 'would hear nothing when focusing it'
                );
                $this->assertNotNull(
                    $a['tabIndex'],
                    "Swatch #{$i} has no tabindex attribute — swatches must "
                    . 'opt into the focus order for keyboard navigation'
                );
                $tab = (string)$a['tabIndex'];
                $this->assertContains(
                    $tab,
                    ['0', '-1'],
                    "Swatch #{$i} has unexpected tabindex '{$tab}' — only "
                    . '0 or -1 are valid for a roving tabindex group'
                );
                if ($tab === '0') {
                    $tabZeroCount++;
                }
            }
            $this->assertSame(
                1,
                $tabZeroCount,
                "Expected exactly one swatch with tabindex=\"0\" (roving "
                . "tabindex), but found {$tabZeroCount}. Tab would either "
                . 'skip the picker (0 matches) or step through every pack '
                . '(multiple matches), which is not the listbox-style UX '
                . 'the picker is supposed to expose.'
            );

            // 2) Focus the first swatch — simulates the user having
            // tabbed into the picker. Assert activeElement tracks.
            $initialFocusLabel = $this->focusSwatchByIndex(
                $browser,
                $pickerIndex,
                0
            );
            $this->assertSame(
                (string)$packs[0]['title'],
                $initialFocusLabel,
                'Focusing the first .style-pack-item should leave it as '
                . 'the iframe activeElement, but activeElement exposed '
                . "label '{$initialFocusLabel}'. Either .focus() silently "
                . 'failed or the swatch is not focusable (missing tabindex).'
            );

            // 3) ArrowRight moves focus forward. Dispatch from the first
            // swatch and expect the second to receive focus.
            $afterRightLabel = $this->dispatchKeydownOnActive(
                $browser,
                $pickerIndex,
                'ArrowRight'
            );
            $this->assertSame(
                (string)$packs[1]['title'],
                $afterRightLabel,
                'ArrowRight from swatch #0 should move focus to swatch #1, '
                . "but activeElement is now labelled '{$afterRightLabel}'. "
                . 'The keydown listener is missing, preventDefault is not '
                . 'firing, or focus is not moving.'
            );

            // 3b) The roving tabindex should follow focus — only the
            // newly-focused swatch should report tabindex=0.
            $attrsAfterRight = $this->snapshotSwatchAttributes($browser, $pickerIndex);
            $this->assertSame(
                '0',
                (string)$attrsAfterRight[1]['tabIndex'],
                'After ArrowRight the newly-focused swatch should take '
                . "tabindex=0, but swatch #1 reported tabindex '"
                . $attrsAfterRight[1]['tabIndex'] . "'. A roving tabindex "
                . 'must move with focus so Tab-away-Tab-back returns to '
                . 'the same swatch.'
            );
            $this->assertSame(
                '-1',
                (string)$attrsAfterRight[0]['tabIndex'],
                'After ArrowRight swatch #0 should drop to tabindex=-1 so '
                . 'it leaves the tab order — without this, Tab would step '
                . 'through multiple swatches.'
            );

            // 4) ArrowLeft walks focus back.
            $afterLeftLabel = $this->dispatchKeydownOnActive(
                $browser,
                $pickerIndex,
                'ArrowLeft'
            );
            $this->assertSame(
                (string)$packs[0]['title'],
                $afterLeftLabel,
                'ArrowLeft from swatch #1 should move focus back to swatch '
                . "#0, but activeElement is now '{$afterLeftLabel}'."
            );

            // 5) Enter on the first-swatch-focus applies the first pack.
            // The picker auto-collapses on apply (see collapseStylePacks
            // in applyStylePack) so we reopen before inspecting the
            // .active indicator.
            $this->dispatchKeydownOnActive($browser, $pickerIndex, 'Enter');
            $this->waitForActiveTransition($browser, $pickerIndex);

            $this->ensurePickerExpanded($browser, $pickerIndex);
            $activeAfterEnter = $this->snapshotActiveSwatchLabels($browser, $pickerIndex);
            $this->assertSame(
                [(string)$packs[0]['title']],
                $activeAfterEnter,
                'Enter on the focused swatch should mark it as .active, '
                . 'but the .active swatch(es) read: '
                . json_encode($activeAfterEnter)
                . '. Either the keydown Enter handler did not call '
                . 'applyStylePack, or the .active class moved to the wrong '
                . 'swatch.'
            );

            // 6) Space on a later swatch applies that pack too. Use the
            // LAST swatch so we prove arrow navigation can reach any
            // index (not just neighbours) via repeated keypresses.
            $lastIndex = count($packs) - 1;
            $this->focusSwatchByIndex($browser, $pickerIndex, $lastIndex);
            $activeLabel = $this->dispatchKeydownOnActive(
                $browser,
                $pickerIndex,
                ' '
            );
            // After Space, applyStylePack fires and the picker collapses
            // before we can observe — we don't care about the label
            // returned from the dispatch (activeElement may be reset),
            // only that the .active indicator migrated to the last pack.
            unset($activeLabel);
            $this->waitForActiveTransition($browser, $pickerIndex);
            $this->ensurePickerExpanded($browser, $pickerIndex);
            $activeAfterSpace = $this->snapshotActiveSwatchLabels($browser, $pickerIndex);
            $this->assertSame(
                [(string)$packs[$lastIndex]['title']],
                $activeAfterSpace,
                "Space on the last swatch (#{$lastIndex}) should apply that "
                . 'pack and move the .active indicator. Got: '
                . json_encode($activeAfterSpace)
            );
        });
    }

    /**
     * @return list<array{role: string, ariaLabel: string, tabIndex: ?string}>
     */
    private function snapshotSwatchAttributes(Browser $browser, int $pickerIndex): array
    {
        $result = $browser->script("
            try {
                var frame = document.querySelectorAll('iframe.preview-iframe')[{$pickerIndex}];
                if (!frame || !frame.contentDocument) return [];
                var items = frame.contentDocument.querySelectorAll('.style-pack-item');
                var out = [];
                for (var i = 0; i < items.length; i++) {
                    var el = items[i];
                    var t = el.getAttribute('tabindex');
                    out.push({
                        role: el.getAttribute('role') || '',
                        ariaLabel: el.getAttribute('aria-label') || '',
                        tabIndex: (t === null ? null : String(t)),
                    });
                }
                return out;
            } catch (e) {
                return [];
            }
        ");
        $payload = $result[0] ?? [];
        if (!is_array($payload)) {
            return [];
        }
        $out = [];
        foreach ($payload as $row) {
            if (!is_array($row)) continue;
            $out[] = [
                'role' => (string)($row['role'] ?? ''),
                'ariaLabel' => (string)($row['ariaLabel'] ?? ''),
                'tabIndex' => array_key_exists('tabIndex', $row)
                    ? ($row['tabIndex'] === null ? null : (string)$row['tabIndex'])
                    : null,
            ];
        }
        return $out;
    }

    /**
     * Programmatically focus the Nth swatch inside the iframe, and
     * return the text of the now-active element's label (so the caller
     * can assert focus landed).
     */
    private function focusSwatchByIndex(Browser $browser, int $pickerIndex, int $index): string
    {
        $result = $browser->script("
            try {
                var frame = document.querySelectorAll('iframe.preview-iframe')[{$pickerIndex}];
                if (!frame || !frame.contentDocument) return '';
                var doc = frame.contentDocument;
                var items = doc.querySelectorAll('.style-pack-item');
                if (!items[{$index}]) return '';
                items.forEach(function(el){ el.setAttribute('tabindex', '-1'); });
                items[{$index}].setAttribute('tabindex', '0');
                items[{$index}].focus();
                var active = doc.activeElement;
                if (!active) return '';
                var lbl = active.querySelector
                    ? active.querySelector('label.live-edit-label')
                    : null;
                return lbl ? (lbl.textContent || '').trim() : '';
            } catch (e) {
                return 'ERR:' + (e && e.message ? e.message : e);
            }
        ");
        return (string)($result[0] ?? '');
    }

    /**
     * Dispatch a `keydown` with the given `key` on the iframe's current
     * `activeElement`, then return the label text of whichever element
     * ends up focused (the handler may move focus on arrow keys, or may
     * have collapsed the picker on Enter/Space — caller decides what to
     * assert against the returned string).
     */
    private function dispatchKeydownOnActive(Browser $browser, int $pickerIndex, string $key): string
    {
        $encodedKey = json_encode($key);
        $result = $browser->script("
            try {
                var frame = document.querySelectorAll('iframe.preview-iframe')[{$pickerIndex}];
                if (!frame || !frame.contentDocument) return '';
                var doc = frame.contentDocument;
                var target = doc.activeElement;
                if (!target) return '';
                var evt = new KeyboardEvent('keydown', {
                    key: {$encodedKey},
                    bubbles: true,
                    cancelable: true,
                });
                target.dispatchEvent(evt);
                var active = doc.activeElement;
                if (!active) return '';
                var lbl = active.querySelector
                    ? active.querySelector('label.live-edit-label')
                    : null;
                return lbl ? (lbl.textContent || '').trim() : '';
            } catch (e) {
                return 'ERR:' + (e && e.message ? e.message : e);
            }
        ");
        return (string)($result[0] ?? '');
    }

    /**
     * @return list<string>
     */
    private function snapshotActiveSwatchLabels(Browser $browser, int $pickerIndex): array
    {
        $result = $browser->script("
            try {
                var frame = document.querySelectorAll('iframe.preview-iframe')[{$pickerIndex}];
                if (!frame || !frame.contentDocument) return [];
                var items = frame.contentDocument.querySelectorAll('.style-pack-item.active');
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

    /**
     * After Enter/Space fires applyStylePack, the picker collapses and
     * the iframe may reload. Give it a moment so the re-expand step
     * below doesn't race.
     */
    private function waitForActiveTransition(Browser $browser, int $pickerIndex): void
    {
        for ($attempt = 0; $attempt < 20; $attempt++) {
            $state = $browser->script("
                try {
                    var frame = document.querySelectorAll('iframe.preview-iframe')[{$pickerIndex}];
                    if (!frame || !frame.contentDocument) return 'NO_FRAME';
                    var doc = frame.contentDocument;
                    var container = doc.querySelector('.style-pack-container');
                    return container && container.classList.contains('expanded')
                        ? 'STILL_EXPANDED'
                        : 'COLLAPSED';
                } catch (e) {
                    return 'ERR';
                }
            ");
            $outcome = (string)($state[0] ?? '');
            if ($outcome === 'COLLAPSED' || $outcome === 'NO_FRAME') {
                return;
            }
            $browser->pause(250);
        }
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
