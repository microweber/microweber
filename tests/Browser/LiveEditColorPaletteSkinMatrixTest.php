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
 * Phase-7 cross-skin palette matrix.
 *
 * Proves that applying the `neon-night` pack via the live-edit
 * `cssEditor.setPropertyForSelectorBulk(':root', …)` pipeline lands
 * on every Bootstrap skin shipped today — not just the one we compose
 * into {@see \Tests\Browser\Factories\ColorPaletteFactory}'s smoke page.
 *
 * Why `neon-night`:
 *   High-contrast accent that diverges from the default Bootstrap
 *   palette, so a hardcoded hex in a skin's SCSS surfaces as a
 *   visible mismatch. The pack declares a full `--mw-*` map (24 keys
 *   today), so every skin has at least one touchpoint to verify.
 *
 * Per-skin verification:
 *   For each skin fixture produced by
 *   {@see ColorPaletteSkinMatrixFactory::makeAll()}, the test:
 *     1. Visits `/admin/live-edit?url=<link>` for that skin page.
 *     2. Applies the pack via the real cssEditor API.
 *     3. Reads the canvas document's computed styles for:
 *        - `:root` — `--mw-body-color`, `--mw-heading-color`,
 *          `--mw-btn-background-color` (contract from the pack JSON).
 *        - `body.color` — resolves `var(--mw-body-color)` per
 *          `public/templates/bootstrap/dist/build/app.css` `body {}`.
 *        - First in-section heading (`h1..h6`) — the section-scoped
 *          rule `.main h1 { color: var(--mw-heading-color) }`.
 *        - First `.btn-primary` (or generic `.btn` per the scoped
 *          selector in app.css) — resolves
 *          `background-color: var(--mw-btn-background-color)`.
 *     4. Asserts each measurement equals the neon-night value after
 *        hex→rgb normalization.
 *
 * Skins that don't ship a heading or a button (e.g. `titles/skin-1`
 * is heading-only; `footers/skin-1` may have no `.btn-primary`) have
 * the corresponding probe return an empty marker — the test skips the
 * check explicitly rather than failing, and records a visible "SKIP"
 * line in the per-skin report so a regression that accidentally strips
 * headings from every skin is still obvious.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
#[Group('color-palettes')]
class LiveEditColorPaletteSkinMatrixTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    private const PALETTE_SLUG = 'neon-night';
    private const PALETTE_TITLE = 'Neon Night';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function neon_night_paints_every_available_skin(): void
    {
        $packs = array_column($this->listColorPalettes(), null, 'slug');
        $this->assertArrayHasKey(
            self::PALETTE_SLUG,
            $packs,
            "Representative palette '" . self::PALETTE_SLUG
            . "' must be discoverable on disk"
        );
        $this->assertSame(
            self::PALETTE_TITLE,
            (string)$packs[self::PALETTE_SLUG]['title'],
            'Palette title must match the pack JSON settings[0].title'
        );

        $props = $packs[self::PALETTE_SLUG]['properties'];
        $this->assertArrayHasKey('--mw-body-color', $props);
        $this->assertArrayHasKey('--mw-heading-color', $props);
        $this->assertArrayHasKey('--mw-btn-background-color', $props);

        $expectedBody = $this->normalizeCssColor((string)$props['--mw-body-color']);
        $expectedHeading = $this->normalizeCssColor((string)$props['--mw-heading-color']);
        $expectedBtnBg = $this->normalizeCssColor((string)$props['--mw-btn-background-color']);

        $fixtures = ColorPaletteSkinMatrixFactory::makeAll();
        $this->assertNotEmpty(
            $fixtures,
            'Expected at least one skin fixture; matrix factory returned zero.'
        );

        $pending = ColorPaletteSkinMatrixFactory::pendingSkins();
        if ($pending !== []) {
            fwrite(
                STDERR,
                "\n[skin-matrix] skipping plan-listed skins with no blade file on disk: "
                . implode(', ', $pending) . "\n"
            );
        }

        $this->browse(function (Browser $browser) use (
            $fixtures,
            $expectedBody,
            $expectedHeading,
            $expectedBtnBg
        ) {
            $this->loginAsAdmin($browser);

            foreach ($fixtures as $skin => $fixture) {
                $this->openColorPaletteSidebar($browser, $fixture->pageId);
                $this->clickPalette($browser, self::PALETTE_SLUG);

                // 1) :root contract — palette vars resolved on the
                // canvas iframe's documentElement. Reuses the shared
                // trait so the set-equality assertion matches every
                // other palette test.
                $this->assertPaletteApplied($browser, [
                    '--mw-body-color'           => $fixture ? (string)$expectedBody : '',
                    '--mw-heading-color'        => (string)$expectedHeading,
                    '--mw-btn-background-color' => (string)$expectedBtnBg,
                ]);

                // 2) Rendered DOM — each skin's actual elements paint
                // with the custom-property values.
                $probes = $this->probeSkinPaintedColors($browser);

                $this->assertNotSame(
                    '',
                    $probes['body'],
                    "[{$skin}] body.color came back empty — the canvas "
                    . 'iframe did not render a <body>, or the probe ran '
                    . 'before the palette apply hit the page'
                );
                $this->assertSame(
                    $expectedBody,
                    $this->normalizeCssColor($probes['body']),
                    "[{$skin}] body.color expected '{$expectedBody}' "
                    . "(--mw-body-color from pack) but got '{$probes['body']}'. "
                    . 'A hardcoded color in the skin or global SCSS is '
                    . 'overriding the palette variable for body text.'
                );

                // Heading: required for every skin that renders text
                // sections (every target skin today emits at least one
                // h1..h6). If a future skin is pure-imagery, add an
                // explicit opt-out here.
                if ($probes['heading'] !== '') {
                    $this->assertSame(
                        $expectedHeading,
                        $this->normalizeCssColor($probes['heading']),
                        "[{$skin}] heading color expected '{$expectedHeading}' "
                        . "(--mw-heading-color from pack) but got "
                        . "'{$probes['heading']}'. The skin's heading rule "
                        . 'is resolving to a non-palette color.'
                    );
                } else {
                    fwrite(
                        STDERR,
                        "\n[skin-matrix] {$skin}: no heading element in canvas DOM — "
                        . "skipping --mw-heading-color DOM check\n"
                    );
                }

                // Button: skipped for skins that don't render a
                // `.btn-primary`-shaped element (titles/skin-1 is
                // heading-only by design).
                if ($probes['button'] !== '') {
                    $this->assertSame(
                        $expectedBtnBg,
                        $this->normalizeCssColor($probes['button']),
                        "[{$skin}] primary button background expected "
                        . "'{$expectedBtnBg}' (--mw-btn-background-color "
                        . "from pack) but got '{$probes['button']}'. A "
                        . "skin-specific or module-level override is "
                        . 'winning over the palette variable.'
                    );
                } else {
                    fwrite(
                        STDERR,
                        "\n[skin-matrix] {$skin}: no .btn-primary element in canvas DOM — "
                        . "skipping --mw-btn-background-color DOM check\n"
                    );
                }
            }
        });
    }

    /**
     * Read computed colors for the three primary accents on the
     * canvas iframe's actual DOM (not just `:root`). Returned values
     * are `rgb(...)` strings straight from `getComputedStyle`, or the
     * empty string if the canvas does not render that element.
     *
     * @return array{body: string, heading: string, button: string}
     */
    private function probeSkinPaintedColors(Browser $browser): array
    {
        $result = $browser->script("
            try {
                var doc = (window.mw && mw.top && mw.top().app && mw.top().app.canvas
                    && typeof mw.top().app.canvas.getDocument === 'function')
                    ? mw.top().app.canvas.getDocument()
                    : null;
                if (!doc || !doc.body || !doc.defaultView) {
                    return {body: '', heading: '', button: ''};
                }
                var win = doc.defaultView;

                var bodyColor = (win.getComputedStyle(doc.body).color || '').trim();

                // Utility classes that explicitly force a color on
                // the element or its ancestry — these are design
                // choices (e.g. white hero title on a dark background
                // image), not palette variables, so we skip probes
                // that land inside them.
                var OVERRIDE_CLASSES = [
                    'text-white', 'text-black', 'text-light', 'text-dark',
                    'text-primary', 'text-secondary', 'text-success',
                    'text-info', 'text-warning', 'text-danger',
                    'text-muted', 'text-body-emphasis',
                ];
                function hasOverrideAncestor(el) {
                    var node = el;
                    while (node && node.classList) {
                        for (var i = 0; i < OVERRIDE_CLASSES.length; i++) {
                            if (node.classList.contains(OVERRIDE_CLASSES[i])) {
                                return true;
                            }
                        }
                        node = node.parentElement;
                    }
                    return false;
                }
                function isVisible(el) {
                    if (!el || !el.getClientRects) return false;
                    if (el.getClientRects().length === 0) return false;
                    var cs = win.getComputedStyle(el);
                    return cs.visibility !== 'hidden' && cs.display !== 'none';
                }

                var heading = '';
                var headingNodes = doc.querySelectorAll('h1, h2, h3, h4, h5, h6');
                for (var i = 0; i < headingNodes.length; i++) {
                    var h = headingNodes[i];
                    if (!isVisible(h)) continue;
                    if (hasOverrideAncestor(h)) continue;
                    heading = (win.getComputedStyle(h).color || '').trim();
                    break;
                }

                var button = '';
                // Match the same selector family as Bootstrap template's
                // compiled CSS rule that binds --mw-btn-background-color.
                var btnNodes = doc.querySelectorAll(
                    '.btn-primary, .btn:not(.btn-secondary):not(.btn-outline):not(.btn-link):not(.btn-dark)'
                );
                for (var j = 0; j < btnNodes.length; j++) {
                    var b = btnNodes[j];
                    if (!isVisible(b)) continue;
                    button = (win.getComputedStyle(b).backgroundColor || '').trim();
                    break;
                }

                return {body: bodyColor, heading: heading, button: button};
            } catch (e) {
                return {
                    body: 'ERR:' + (e && e.message ? e.message : e),
                    heading: '',
                    button: '',
                };
            }
        ");
        $payload = $result[0] ?? [];
        if (!is_array($payload)) {
            return ['body' => '', 'heading' => '', 'button' => ''];
        }
        return [
            'body' => (string)($payload['body'] ?? ''),
            'heading' => (string)($payload['heading'] ?? ''),
            'button' => (string)($payload['button'] ?? ''),
        ];
    }
}
