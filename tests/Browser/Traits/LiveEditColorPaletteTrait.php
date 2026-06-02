<?php

namespace Tests\Browser\Traits;

use Laravel\Dusk\Browser;

/**
 * Shared helpers for Dusk tests that exercise the live-edit color
 * palette picker (Bootstrap template style-packs under
 * resources/assets/design-styles/style-packs/colors/).
 *
 * The trait deliberately drives the same JavaScript API the Vue picker
 * components (FieldColorPalette.vue / FieldStylePack.vue) call when a
 * user clicks a swatch — `mw.top().app.cssEditor.setPropertyForSelectorBulk(':root', ..., true, true)`.
 * Driving the apply API directly (instead of clicking iframe-rendered
 * swatches in the sidebar) keeps the tests robust to sidebar markup
 * churn while still exercising the real cssEditor apply pipeline,
 * undo/redo state recording, dispatch hooks, and CSS text writes.
 */
trait LiveEditColorPaletteTrait
{
    /**
     * Enumerate every color pack under Bootstrap's style-packs/colors
     * folder. Each entry has `slug` (filename without extension),
     * `title` (settings[0].title) and `properties` (the CSS custom
     * property map at settings[0].fieldSettings.styleProperties[0].properties).
     *
     * Returned entries are sorted by slug so data-provider order is
     * deterministic across runs and hosts.
     *
     * @return array<int, array{slug: string, title: string, properties: array<string, string>}>
     */
    protected function listColorPalettes(): array
    {
        $root = function_exists('base_path') ? base_path() : dirname(__DIR__, 3);
        $dir = $root . '/Templates/Bootstrap/resources/assets/design-styles/style-packs/colors';

        if (!is_dir($dir)) {
            return [];
        }

        $packs = [];
        foreach (glob($dir . '/*.json') ?: [] as $file) {
            $raw = @file_get_contents($file);
            if ($raw === false || $raw === '') {
                continue;
            }
            $data = json_decode($raw, true);
            if (!is_array($data)) {
                continue;
            }
            $settings = $data['settings'][0] ?? null;
            if (!is_array($settings)) {
                continue;
            }
            $properties = $settings['fieldSettings']['styleProperties'][0]['properties'] ?? null;
            if (!is_array($properties) || $properties === []) {
                continue;
            }

            $packs[] = [
                'slug' => pathinfo($file, PATHINFO_FILENAME),
                'title' => $settings['title'] ?? pathinfo($file, PATHINFO_FILENAME),
                'properties' => $properties,
            ];
        }

        usort($packs, static fn(array $a, array $b): int => strcmp($a['slug'], $b['slug']));

        return $packs;
    }

    /**
     * Open the page in live-edit and wait until the top-frame cssEditor
     * is ready. The color picker sidebar doesn't need to be opened for
     * {@see clickPalette()} to work — it calls the same `setPropertyForSelectorBulk`
     * API that the sidebar's Vue components invoke on swatch click.
     */
    protected function openColorPaletteSidebar(Browser $browser, int $pageId, string $link = ''): void
    {
        if ($link === '') {
            $link = (string)content_link($pageId);
        }
        if (!$link) {
            throw new \RuntimeException(
                "openColorPaletteSidebar: content_link({$pageId}) returned empty"
            );
        }

        $browser->visit('/admin/live-edit?url=' . urlencode($link))->pause(5000);
        $browser->waitFor('iframe', 20)->pause(3000);

        for ($i = 0; $i < 30; $i++) {
            $ready = $browser->script(
                "return (typeof window.mw !== 'undefined'
                    && window.mw.top
                    && window.mw.top().app
                    && window.mw.top().app.cssEditor
                    && typeof window.mw.top().app.cssEditor.setPropertyForSelectorBulk === 'function'
                ) ? 1 : 0;"
            );
            if (($ready[0] ?? 0) === 1) {
                return;
            }
            $browser->pause(500);
        }

        throw new \RuntimeException(
            'openColorPaletteSidebar: mw.top().app.cssEditor never became ready within 15s'
        );
    }

    /**
     * Apply the pack identified by $slug on `:root` via the real
     * cssEditor API. Mirrors the swatch-click path used by
     * FieldColorPalette.vue and FieldStylePack.vue.
     */
    protected function clickPalette(Browser $browser, string $slug): void
    {
        $pack = null;
        foreach ($this->listColorPalettes() as $candidate) {
            if ($candidate['slug'] === $slug) {
                $pack = $candidate;
                break;
            }
        }
        if ($pack === null) {
            throw new \RuntimeException("clickPalette: no pack matched slug '{$slug}'");
        }

        $propsJson = json_encode($pack['properties'], JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);

        $result = $browser->script(
            "try {
                var cssEditor = window.mw && mw.top && mw.top().app && mw.top().app.cssEditor;
                if (!cssEditor || typeof cssEditor.setPropertyForSelectorBulk !== 'function') {
                    return 'NO_EDITOR';
                }
                cssEditor.setPropertyForSelectorBulk(':root', {$propsJson}, true, true);
                return 'OK';
            } catch (e) {
                return 'ERR:' + (e && e.message ? e.message : e);
            }"
        );

        $outcome = $result[0] ?? 'UNKNOWN';
        if ($outcome !== 'OK') {
            throw new \RuntimeException("clickPalette('{$slug}'): {$outcome}");
        }

        $browser->pause(800);
    }

    /**
     * Snapshot every `--mw-*` CSS custom property currently in effect on
     * the canvas document's `:root`. The live-edit picker writes to the
     * canvas iframe (via `mw.top().app.cssEditor` → `mw.app.canvas.getDocument()`),
     * not the admin frame, so the computed styles must be read from the
     * canvas iframe's `document.documentElement`.
     *
     * @return array<string, string>
     */
    protected function snapshotRootCssVars(Browser $browser): array
    {
        $result = $browser->script(
            "try {
                var doc = (window.mw && mw.top && mw.top().app && mw.top().app.canvas
                    && typeof mw.top().app.canvas.getDocument === 'function')
                    ? mw.top().app.canvas.getDocument()
                    : null;
                if (!doc || !doc.documentElement) {
                    return {};
                }
                var styles = doc.defaultView.getComputedStyle(doc.documentElement);
                var out = {};
                for (var i = 0; i < styles.length; i++) {
                    var prop = styles[i];
                    if (prop && prop.indexOf('--') === 0) {
                        out[prop] = (styles.getPropertyValue(prop) || '').trim();
                    }
                }
                return out;
            } catch (e) {
                return {};
            }"
        );

        $vars = $result[0] ?? [];
        return is_array($vars) ? $vars : [];
    }

    /**
     * Assert every property in $expected is present on `:root` and its
     * computed value matches (normalized). Hex values are folded to
     * rgb() tuples so the computed-style reply from Chrome doesn't
     * produce false negatives when the pack ships #rrggbb.
     *
     * @param array<string, string> $expected
     */
    protected function assertPaletteApplied(Browser $browser, array $expected): void
    {
        $actual = $this->snapshotRootCssVars($browser);

        foreach ($expected as $prop => $expectedValue) {
            $this->assertArrayHasKey(
                $prop,
                $actual,
                "Palette property '{$prop}' is missing from :root after apply"
            );

            $exp = $this->normalizeCssColor((string)$expectedValue);
            $got = $this->normalizeCssColor((string)$actual[$prop]);

            $this->assertSame(
                $exp,
                $got,
                "Palette property '{$prop}' expected '{$expectedValue}' but :root has '{$actual[$prop]}'"
            );
        }
    }

    /**
     * Fold a CSS color value to a canonical form so #ffffff, rgb(255,255,255)
     * and rgb(255, 255, 255) compare equal. Non-color values (keywords,
     * var(...)) are returned lowercased with interior whitespace collapsed
     * so comparisons remain predictable.
     */
    protected function normalizeCssColor(string $value): string
    {
        $v = strtolower(trim($value));
        if ($v === '') {
            return $v;
        }

        if (preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/', $v, $m) === 1) {
            $hex = $m[1];
            if (strlen($hex) === 3) {
                $r = hexdec($hex[0] . $hex[0]);
                $g = hexdec($hex[1] . $hex[1]);
                $b = hexdec($hex[2] . $hex[2]);
            } else {
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
            }
            return "rgb({$r}, {$g}, {$b})";
        }

        if (preg_match('/^rgba?\(([^)]+)\)$/', $v, $m) === 1) {
            $parts = array_map('trim', explode(',', $m[1]));
            return (str_starts_with($v, 'rgba') ? 'rgba' : 'rgb') . '(' . implode(', ', $parts) . ')';
        }

        return preg_replace('/\s+/', ' ', $v);
    }

    /**
     * Snapshot every `--mw-*` CSS custom property from the current page's
     * `:root` directly (not the live-edit canvas iframe). Use this when
     * the browser is on the PUBLIC page URL rather than the admin live-edit
     * URL, so that heavy live-edit JS does not need to be loaded.
     *
     * @return array<string, string>
     */
    protected function snapshotPublicRootCssVars(Browser $browser): array
    {
        $result = $browser->script(
            "try {
                var styles = document.defaultView.getComputedStyle(document.documentElement);
                var out = {};
                for (var i = 0; i < styles.length; i++) {
                    var prop = styles[i];
                    if (prop && prop.indexOf('--') === 0) {
                        out[prop] = (styles.getPropertyValue(prop) || '').trim();
                    }
                }
                return out;
            } catch (e) {
                return {};
            }"
        );

        $vars = $result[0] ?? [];
        return is_array($vars) ? $vars : [];
    }

    /**
     * and block until it completes. Matches the contract used by
     * {@see \Tests\Browser\Traits\LiveEditPageBuilderTrait::saveLiveEdit()}
     * so palette-only tests don't need to compose the full landing-page
     * builder just to Save.
     *
     * Two-phase save to avoid the CSS-publish race condition:
     *   Phase 1 — explicitly flush cssEditor.publishIfChanged() and wait
     *             for its Promise to resolve before starting the HTML save.
     *             mw.drag.save() calls publishIfChanged() internally but does
     *             NOT await it, so the HTML XHR can complete (setting
     *             __liveEditSaveDone) while the CSS POST is still in flight.
     *   Phase 2 — call mw.drag.save() and wait for the HTML XHR as before.
     *             Because Phase 1 already set changed = false, the internal
     *             publishIfChanged() call inside drag.save() is a no-op.
     */
    protected function saveLiveEdit(Browser $browser): void
    {
        // Phase 1: flush CSS editor before HTML save.
        $browser->script(
            "window.__liveEditCssDone = false;
            window.__liveEditCssError = null;

            var cssEditor = window.mw && mw.app && mw.app.cssEditor;
            if (!cssEditor || typeof cssEditor.publishIfChanged !== 'function') {
                window.__liveEditCssDone = true;
            } else {
                try {
                    cssEditor.publishIfChanged()
                        .then(function () { window.__liveEditCssDone = true; })
                        .catch(function (e) {
                            window.__liveEditCssError = 'cssPublish rejected: ' + (e && e.message ? e.message : e);
                            window.__liveEditCssDone = true;
                        });
                } catch (e) {
                    window.__liveEditCssError = 'publishIfChanged threw: ' + (e && e.message ? e.message : e);
                    window.__liveEditCssDone = true;
                }
            }"
        );

        for ($i = 0; $i < 30; $i++) {
            $cssState = $browser->script(
                "return { done: !!window.__liveEditCssDone, error: window.__liveEditCssError };"
            );
            $cssState = $cssState[0] ?? ['done' => false, 'error' => null];
            if (!empty($cssState['done'])) {
                break;
            }
            $browser->pause(500);
        }

        // Phase 2: HTML save.
        $browser->script(
            "window.__liveEditSaveDone = false;
            window.__liveEditSaveError = null;

            if (!(window.mw && mw.app && mw.app.canvas
                && typeof mw.app.canvas.getWindow === 'function')) {
                window.__liveEditSaveError = 'saveLiveEdit: mw.app.canvas.getWindow not available';
                return;
            }

            var canvasWindow;
            try {
                canvasWindow = mw.app.canvas.getWindow();
            } catch (e) {
                window.__liveEditSaveError = 'saveLiveEdit: getWindow threw: ' + (e && e.message ? e.message : e);
                return;
            }
            if (!canvasWindow || !canvasWindow.mw) {
                window.__liveEditSaveError = 'saveLiveEdit: canvas iframe has no mw global yet';
                return;
            }

            var xhr = null;
            try {
                if (canvasWindow.mw.drag && typeof canvasWindow.mw.drag.save === 'function') {
                    xhr = canvasWindow.mw.drag.save();
                } else if (canvasWindow.mw.liveEditSaveService
                    && typeof canvasWindow.mw.liveEditSaveService.save === 'function') {
                    xhr = canvasWindow.mw.liveEditSaveService.save();
                } else {
                    window.__liveEditSaveError = 'saveLiveEdit: canvasWindow.mw.drag.save / liveEditSaveService.save missing';
                    return;
                }
            } catch (e) {
                window.__liveEditSaveError = 'saveLiveEdit: save threw: ' + (e && e.message ? e.message : e);
                return;
            }

            if (xhr && typeof xhr.always === 'function') {
                xhr.always(function () { window.__liveEditSaveDone = true; });
            } else {
                window.__liveEditSaveDone = true;
            }"
        );

        for ($i = 0; $i < 40; $i++) {
            $state = $browser->script(
                "return {
                    done: !!window.__liveEditSaveDone,
                    error: window.__liveEditSaveError
                };"
            );
            $state = $state[0] ?? ['done' => false, 'error' => null];
            if (!empty($state['error'])) {
                throw new \RuntimeException("saveLiveEdit: {$state['error']}");
            }
            if (!empty($state['done'])) {
                return;
            }
            $browser->pause(500);
        }

        throw new \RuntimeException('saveLiveEdit: save XHR never completed within 20s');
    }
}
