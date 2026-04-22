<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\ColorPaletteSkinMatrixFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansColorPaletteTestFixtures;
use Tests\Browser\Traits\LiveEditColorPaletteTrait;
use Tests\DuskTestCase;

/**
 * Phase-7 CSS-specificity regression guard.
 *
 * The matrix test ({@see LiveEditColorPaletteSkinMatrixTest}) proves
 * that applying `neon-night` paints the right body/heading/button
 * colors on every skin. This sibling answers the orthogonal question:
 * after the apply, does the skin *still* render any element with a
 * color inherited from a previous palette or from the Bootstrap
 * template's default `design-vars.css` fallback?
 *
 * What this catches (that the positive-paint test can't):
 *   - A hardcoded hex in a skin's compiled CSS that overrides the
 *     `var(--mw-*)` binding at a higher specificity — every skin
 *     `body.color` test might still pass (because `body {}` is
 *     matched first), while a `.pricing-skin-1 .card__title` rule
 *     with a literal `#f4a261` silently renders the default accent.
 *   - A stale `:root` override left behind by a previous apply (two
 *     `setPropertyForSelectorBulk(':root')` calls racing, or the
 *     apply function forgetting to remove keys not present in the
 *     second pack — the old palette's hex would leak on every
 *     element that uses a var the new pack doesn't redefine).
 *
 * Test flow (per skin page):
 *   1. Apply palette A (`apple-shine`) via the shared trait.
 *   2. Apply palette B (`neon-night`) — same API, same target.
 *   3. Scan every visible element in the canvas iframe's `<body>`
 *      and collect its computed `color` and `background-color`.
 *   4. Assert none of the scanned values matches:
 *      - any of palette A's distinctive values for those properties
 *        (would mean A leaked through B's apply), OR
 *      - any of the default `design-vars.css` accent values (would
 *        mean a hardcoded hex somewhere broke the variable chain).
 *
 * Known-false-positive exemptions:
 *   - `#ffffff` / `rgb(255,255,255)` — shared between many palettes
 *     (both B's `--mw-btn-text-color` and apple-shine's, plus the
 *     default `--mw-background-color`) so its presence isn't a leak.
 *   - Transparent / rgba(0,0,0,0) — means "no paint", can't leak.
 *   - Elements inside Bootstrap `text-*` utility overrides (same
 *     exemption the matrix test applies).
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
#[Group('color-palettes')]
class LiveEditColorPaletteSkinMatrixNoLeakTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    private const PREVIOUS_PALETTE = 'apple-shine';
    private const CURRENT_PALETTE = 'neon-night';

    /**
     * Properties we probe on each skin. Restricted to values that
     * every target pack defines AND whose selectors actually render
     * on at least one skin (so a leak has somewhere to show).
     */
    private const LEAK_CHECK_PROPS = [
        '--mw-body-color',
        '--mw-heading-color',
        '--mw-btn-background-color',
        '--mw-primary-color',
        '--mw-link-color',
    ];

    /**
     * Default-template fallback values from
     * `Templates/Bootstrap/resources/assets/design-styles/design-vars.css`.
     * If a skin's computed-style for a palette-bound property equals
     * one of these AFTER a palette apply, then a hardcoded hex at
     * higher specificity is winning — the regression this test exists
     * to catch.
     *
     * Kept as hex so the test's intent stays readable; the comparison
     * folds both sides to rgb() via normalizeCssColor().
     *
     * @var list<string>
     */
    private const DEFAULT_TEMPLATE_FALLBACKS = [
        '#f4a261', // primary / link / btn-bg / btn-border / header-link
        '#212529', // textDark / textLight
    ];

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function skins_drop_previous_palette_and_default_fallback_after_apply(): void
    {
        $packs = array_column($this->listColorPalettes(), null, 'slug');
        $this->assertArrayHasKey(self::PREVIOUS_PALETTE, $packs);
        $this->assertArrayHasKey(self::CURRENT_PALETTE, $packs);

        $prevProps = $packs[self::PREVIOUS_PALETTE]['properties'];
        $curProps = $packs[self::CURRENT_PALETTE]['properties'];

        // Build the forbidden-colors set: everything apple-shine painted
        // on a probed property, plus the template fallbacks, minus any
        // value neon-night itself shares with them (so "no leak" isn't a
        // false positive when both palettes happen to agree).
        $forbidden = [];
        foreach (self::LEAK_CHECK_PROPS as $prop) {
            $prev = $this->normalizeCssColor((string)($prevProps[$prop] ?? ''));
            $cur = $this->normalizeCssColor((string)($curProps[$prop] ?? ''));
            if ($prev !== '' && $prev !== $cur) {
                $forbidden[$prev] = self::PREVIOUS_PALETTE . '→' . $prop;
            }
        }
        foreach (self::DEFAULT_TEMPLATE_FALLBACKS as $hex) {
            $n = $this->normalizeCssColor($hex);
            $isPaintedByCurrent = false;
            foreach (self::LEAK_CHECK_PROPS as $prop) {
                if ($this->normalizeCssColor((string)($curProps[$prop] ?? '')) === $n) {
                    $isPaintedByCurrent = true;
                    break;
                }
            }
            if (!$isPaintedByCurrent && $n !== '') {
                $forbidden[$n] = 'default-template';
            }
        }

        $this->assertNotEmpty(
            $forbidden,
            'Forbidden-color set must be non-empty — if every property '
            . 'apple-shine and the template defaults declare happens to '
            . "also be in neon-night, the test can't prove anything."
        );

        $fixtures = ColorPaletteSkinMatrixFactory::makeAll();
        $this->assertNotEmpty($fixtures);

        $this->browse(function (Browser $browser) use ($fixtures, $forbidden) {
            $this->loginAsAdmin($browser);

            foreach ($fixtures as $skin => $fixture) {
                $this->openColorPaletteSidebar($browser, $fixture->pageId);

                // 1) Seed the leak: apply A first so anything that
                // hardcodes a variable override or forgets to reset
                // on re-apply has something to leak FROM.
                $this->clickPalette($browser, self::PREVIOUS_PALETTE);

                // 2) The real apply we're guarding.
                $this->clickPalette($browser, self::CURRENT_PALETTE);

                // 3) Scan the canvas for any rendered color/background
                // matching the forbidden set.
                $leaks = $this->scanForbiddenColors($browser, array_keys($forbidden));

                $this->assertSame(
                    [],
                    $leaks,
                    "[{$skin}] Found rendered colors matching palette A "
                    . 'or default-template fallback after applying '
                    . self::CURRENT_PALETTE . '. Each entry below is '
                    . 'a (selector → color → source) triple — the "source" '
                    . 'tells you whether the leak is a previous-palette '
                    . 'value or a default-template value, which narrows '
                    . "the fix (missing reset vs hardcoded hex):\n"
                    . $this->formatLeakReport($leaks, $forbidden)
                );
            }
        });
    }

    /**
     * Walk every visible element in the canvas iframe's body and
     * return those whose computed color or background-color falls
     * into $forbidden (normalized rgb strings).
     *
     * Returned rows are:
     *   [{selector: string, property: 'color'|'background-color', value: string}, ...]
     *
     * @param list<string> $forbidden normalized rgb() strings
     * @return list<array{selector: string, property: string, value: string}>
     */
    private function scanForbiddenColors(Browser $browser, array $forbidden): array
    {
        $forbiddenJson = json_encode(
            array_values($forbidden),
            JSON_UNESCAPED_SLASHES
        );

        $result = $browser->script("
            try {
                var doc = (window.mw && mw.top && mw.top().app && mw.top().app.canvas
                    && typeof mw.top().app.canvas.getDocument === 'function')
                    ? mw.top().app.canvas.getDocument()
                    : null;
                if (!doc || !doc.body || !doc.defaultView) {
                    return [];
                }
                var win = doc.defaultView;
                var forbidden = {$forbiddenJson};
                var forbiddenSet = {};
                for (var i = 0; i < forbidden.length; i++) {
                    forbiddenSet[forbidden[i]] = true;
                }

                function normalize(v) {
                    // The canvas iframe returns computed colors as
                    // rgb(r, g, b) or rgba(r, g, b, a). Collapse to
                    // the same rgb() canonical form PHP uses so both
                    // sides compare equal.
                    v = (v || '').trim().toLowerCase();
                    if (!v) return '';
                    var m = v.match(/^rgba?\\(\\s*(\\d+)\\s*,\\s*(\\d+)\\s*,\\s*(\\d+)(?:\\s*,\\s*([0-9.]+))?\\s*\\)\$/);
                    if (!m) return v;
                    var r = parseInt(m[1], 10);
                    var g = parseInt(m[2], 10);
                    var b = parseInt(m[3], 10);
                    var a = m[4] === undefined ? 1 : parseFloat(m[4]);
                    if (a === 0) return 'rgba(0, 0, 0, 0)';
                    return 'rgb(' + r + ', ' + g + ', ' + b + ')';
                }

                var OVERRIDE_CLASSES = [
                    'text-white','text-black','text-light','text-dark',
                    'text-primary','text-secondary','text-success',
                    'text-info','text-warning','text-danger','text-muted',
                    'text-body-emphasis','bg-white','bg-dark','bg-light',
                    'bg-primary','bg-secondary','bg-success','bg-info',
                    'bg-warning','bg-danger',
                ];
                function hasOverrideAncestor(el) {
                    var n = el;
                    while (n && n.classList) {
                        for (var i = 0; i < OVERRIDE_CLASSES.length; i++) {
                            if (n.classList.contains(OVERRIDE_CLASSES[i])) {
                                return true;
                            }
                        }
                        n = n.parentElement;
                    }
                    return false;
                }
                function isVisible(el) {
                    if (!el || !el.getClientRects) return false;
                    if (el.getClientRects().length === 0) return false;
                    var cs = win.getComputedStyle(el);
                    return cs.visibility !== 'hidden' && cs.display !== 'none';
                }
                function describe(el) {
                    var tag = el.tagName.toLowerCase();
                    var cls = el.className;
                    if (typeof cls !== 'string') cls = '';
                    var first = cls.split(/\\s+/).filter(Boolean)[0] || '';
                    return first ? (tag + '.' + first) : tag;
                }

                // Only inspect the edit region — the template wraps
                // admin chrome (header bar, sidebar) around the canvas
                // iframe, so scanning doc.body would hit the shell
                // too. In /admin/live-edit the canvas IS the iframe,
                // so doc.body is the rendered page itself.
                var all = doc.body.querySelectorAll('*');
                var leaks = [];
                for (var j = 0; j < all.length; j++) {
                    var el = all[j];
                    if (!isVisible(el)) continue;
                    if (hasOverrideAncestor(el)) continue;

                    // Skip elements the admin live-edit harness injects
                    // on top of the page DOM (toolbars, handles). They
                    // live under the `.mw-*` adminish roots and are
                    // unrelated to the palette under test.
                    var hasAdminClass = false;
                    var n = el;
                    while (n && n.classList) {
                        if (n.classList.contains('mw-admin-bar')
                            || n.classList.contains('mw-live-edit')
                            || n.classList.contains('mw-ui-tooltip')
                            || n.classList.contains('mw-handle')) {
                            hasAdminClass = true; break;
                        }
                        n = n.parentElement;
                    }
                    if (hasAdminClass) continue;

                    var cs = win.getComputedStyle(el);
                    var color = normalize(cs.color);
                    var bg = normalize(cs.backgroundColor);
                    if (color && forbiddenSet[color]) {
                        leaks.push({selector: describe(el), property: 'color', value: color});
                    }
                    if (bg && forbiddenSet[bg]) {
                        leaks.push({selector: describe(el), property: 'background-color', value: bg});
                    }
                    if (leaks.length > 50) break; // cap noise
                }
                return leaks;
            } catch (e) {
                return [{selector: 'ERR', property: 'err', value: (e && e.message ? e.message : String(e))}];
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
                'selector' => (string)($row['selector'] ?? ''),
                'property' => (string)($row['property'] ?? ''),
                'value' => (string)($row['value'] ?? ''),
            ];
        }
        return $out;
    }

    /**
     * Turn a leak list into a human-readable multi-line report,
     * annotating each row with where the forbidden color came from
     * (previous palette or default template).
     *
     * @param list<array{selector: string, property: string, value: string}> $leaks
     * @param array<string, string> $forbidden normalized-rgb → source
     */
    private function formatLeakReport(array $leaks, array $forbidden): string
    {
        $out = [];
        foreach ($leaks as $leak) {
            $src = $forbidden[$leak['value']] ?? 'unknown';
            $out[] = '  - ' . $leak['selector']
                . ' ' . $leak['property']
                . ' = ' . $leak['value']
                . ' (source: ' . $src . ')';
        }
        return $out === [] ? '  (empty)' : implode("\n", $out);
    }
}
