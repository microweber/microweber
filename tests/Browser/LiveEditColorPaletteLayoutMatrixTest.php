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
 * Plan D.2 — color-palette × layout cross-matrix.
 *
 * The existing {@see LiveEditColorPaletteSkinMatrixTest} pairs a
 * single representative palette (`neon-night`) with every shipped
 * Bootstrap skin so a hardcoded hex in any skin's SCSS surfaces
 * as a paint mismatch. This widened matrix proves the inverse —
 * **every** shipped palette lands cleanly on **every** shipped
 * skin, not just the representative one. A regression in any
 * (palette, skin) combination — e.g. a new pack that ships an
 * incomplete `--mw-*` map, or an existing skin that hardcodes a
 * color one specific pack relies on — is caught here.
 *
 * ## Layout
 *
 *   - 17 palette packs (see Plan D.1) × 14 available skins (see
 *     ColorPaletteSkinMatrixFactory::TARGET_SKINS) = up to 238
 *     pairs. The factory's `availableSkins()` filter drops skins
 *     whose blade file isn't shipped yet, so the actual pair count
 *     can be lower (today: 17 × 14 = 238).
 *   - Per-skin shape: open the skin's fixture page in live-edit
 *     ONCE, then iterate every palette against the same loaded
 *     iframe — `cssEditor.setPropertyForSelectorBulk(':root', …)`
 *     re-applies in-place so navigation cost is paid once per
 *     skin, not once per pair. Empirically this cuts the full
 *     matrix from ~40min (one nav per pair) to ~8-10min (one nav
 *     per skin).
 *   - Per pair the test asserts:
 *       1. The pack's `--mw-body-color`, `--mw-heading-color`,
 *          `--mw-btn-background-color` land on `:root` in the
 *          canvas iframe (plan D.3 first bullet — full var map
 *          per pair sample).
 *       2. The skin's first visible non-overridden heading
 *          resolves `var(--mw-heading-color)` to the pack's
 *          declared value (plan D.3 second bullet — concrete
 *          consumer).
 *       3. The skin's first visible primary-button background
 *          resolves `var(--mw-btn-background-color)` to the
 *          pack's declared value.
 *     Body color is asserted against the per-pair expected value;
 *     heading/button are skipped (with a visible `[skin-matrix]`
 *     stderr line) for skins that genuinely don't render the
 *     element (e.g. `titles/skin-1` is heading-only;
 *     `footers/skin-1` may have no `.btn-primary`).
 *
 * ## Chunking
 *
 *   Plan D.2 second bullet asks the matrix split into chunks Dusk
 *   can run in parallel. Each chunk method below carries:
 *     - `#[Group('color-palette-layout-matrix')]` — full-matrix
 *       group, run with `--group=color-palette-layout-matrix` to
 *       sequentially exercise every chunk in the same process.
 *     - `#[Group('color-palette-layout-chunk-N')]` — per-chunk
 *       group, so CI workers can split via
 *       `--group=color-palette-layout-chunk-1`, `…chunk-2`, etc.
 *       and run chunks in parallel processes.
 *   Today the matrix is split into 4 skin slices (3 + 3 + 4 + 4
 *   skins). Each chunk processes ~17 palettes × ~3-4 skins =
 *   51-68 pair-applies. Chunks are skin-bounded (not palette-
 *   bounded) so the navigation cost (10s/skin) parallelizes the
 *   most.
 *
 * ## Cleanup
 *
 *   Reuses {@see CleansColorPaletteTestFixtures} so leftover
 *   `color-palette-skin-test-*` rows from prior runs are purged
 *   before AND after each chunk. Plan D.3 third-bullet contract
 *   ("zero fixture residue").
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin.
 */
#[Group('color-palettes')]
class LiveEditColorPaletteLayoutMatrixTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    /**
     * Skin slice for chunk 1 — the jumbotron + first features
     * skins. Visited once each per chunk run; every palette is
     * applied against each in turn.
     */
    private const CHUNK_1_SKINS = [
        'jumbotron/skin-1',
        'jumbotron/skin-2',
        'features/skin-1',
    ];

    private const CHUNK_2_SKINS = [
        'features/skin-2',
        'pricing/skin-1',
        'pricing/skin-2',
    ];

    private const CHUNK_3_SKINS = [
        'pricing/skin-3',
        'titles/skin-1',
        'content/skin-1',
        'blog/skin-1',
    ];

    private const CHUNK_4_SKINS = [
        'ecommerce/skin-1',
        'footers/skin-1',
        'text-block/skin-1',
        'menus/skin-1',
    ];

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    #[Test]
    #[Group('color-palette-layout-matrix')]
    #[Group('color-palette-layout-chunk-1')]
    public function chunk_1_every_palette_paints_every_skin_in_chunk_1(): void
    {
        $this->runMatrixForSkins(self::CHUNK_1_SKINS);
    }

    #[Test]
    #[Group('color-palette-layout-matrix')]
    #[Group('color-palette-layout-chunk-2')]
    public function chunk_2_every_palette_paints_every_skin_in_chunk_2(): void
    {
        $this->runMatrixForSkins(self::CHUNK_2_SKINS);
    }

    #[Test]
    #[Group('color-palette-layout-matrix')]
    #[Group('color-palette-layout-chunk-3')]
    public function chunk_3_every_palette_paints_every_skin_in_chunk_3(): void
    {
        $this->runMatrixForSkins(self::CHUNK_3_SKINS);
    }

    #[Test]
    #[Group('color-palette-layout-matrix')]
    #[Group('color-palette-layout-chunk-4')]
    public function chunk_4_every_palette_paints_every_skin_in_chunk_4(): void
    {
        $this->runMatrixForSkins(self::CHUNK_4_SKINS);
    }

    /**
     * Drive the per-skin live-edit pipeline: for each skin in
     * $skinSlice, open its fixture page in live-edit once, then
     * iterate every palette pack on disk and apply it via the
     * cssEditor API. Per pair, snapshot `:root` + skin-painted
     * DOM colors and assert they match the pack's declared
     * values.
     *
     * @param array<int, string> $skinSlice
     */
    private function runMatrixForSkins(array $skinSlice): void
    {
        $packs = $this->listColorPalettes();
        $this->assertNotEmpty(
            $packs,
            'Plan D.2 matrix: expected at least one color pack on disk under '
            . 'Templates/Bootstrap/resources/assets/design-styles/style-packs/colors/. '
            . 'Either the disk inventory regressed or the trait\'s scan path drifted.'
        );

        $available = ColorPaletteSkinMatrixFactory::availableSkins();
        $skinsForChunk = array_values(array_intersect($skinSlice, $available));

        if ($skinsForChunk === []) {
            $missing = array_values(array_diff($skinSlice, $available));
            $this->markTestSkipped(
                'Plan D.2 matrix chunk: every skin in this chunk is missing from disk: '
                . implode(', ', $missing) . '. Add the matching blade file under '
                . 'Templates/Bootstrap/resources/views/modules/layouts/templates/ '
                . 'or remove the skin from the chunk constant.'
            );
        }

        $fixtures = [];
        foreach ($skinsForChunk as $skin) {
            $fixtures[$skin] = ColorPaletteSkinMatrixFactory::makeForSkin($skin);
        }

        $this->browse(function (Browser $browser) use ($fixtures, $packs) {
            $this->loginAsAdmin($browser);

            foreach ($fixtures as $skin => $fixture) {
                $this->openColorPaletteSidebar($browser, $fixture->pageId);

                foreach ($packs as $pack) {
                    $slug = (string) $pack['slug'];
                    $properties = $pack['properties'];

                    $this->assertArrayHasKey(
                        '--mw-body-color',
                        $properties,
                        "Plan D.2 matrix [{$slug} × {$skin}]: pack must declare "
                        . '--mw-body-color — every Bootstrap pack ships this key '
                        . 'today, and the skin\'s body resolves it on every page. '
                        . 'A pack with a missing key would silently leave the '
                        . 'browser default body color in place.'
                    );
                    $this->assertArrayHasKey(
                        '--mw-heading-color',
                        $properties,
                        "Plan D.2 matrix [{$slug} × {$skin}]: pack must declare "
                        . '--mw-heading-color — the skin\'s headings resolve it '
                        . 'on every paint pass.'
                    );
                    $this->assertArrayHasKey(
                        '--mw-btn-background-color',
                        $properties,
                        "Plan D.2 matrix [{$slug} × {$skin}]: pack must declare "
                        . '--mw-btn-background-color — the skin\'s primary buttons '
                        . 'resolve it on every paint pass.'
                    );

                    $expectedBody = $this->normalizeCssColor((string) $properties['--mw-body-color']);
                    $expectedHeading = $this->normalizeCssColor((string) $properties['--mw-heading-color']);
                    $expectedBtnBg = $this->normalizeCssColor((string) $properties['--mw-btn-background-color']);

                    $this->clickPalette($browser, $slug);

                    // 1) :root contract — palette vars resolved
                    // on the canvas iframe's documentElement
                    // (Plan D.3 first bullet).
                    $this->assertPaletteApplied($browser, [
                        '--mw-body-color'           => (string) $properties['--mw-body-color'],
                        '--mw-heading-color'        => (string) $properties['--mw-heading-color'],
                        '--mw-btn-background-color' => (string) $properties['--mw-btn-background-color'],
                    ]);

                    // 2) Concrete consumers — body / heading /
                    // button computed colors (Plan D.3 second
                    // bullet). Same probe shape as the existing
                    // single-palette skin matrix test.
                    $probes = $this->probeSkinPaintedColors($browser);

                    $this->assertNotSame(
                        '',
                        $probes['body'],
                        "Plan D.2 matrix [{$slug} × {$skin}]: body.color came back "
                        . 'empty — the canvas iframe did not render a <body>, or '
                        . 'the probe ran before the palette apply hit the page.'
                    );
                    $this->assertSame(
                        $expectedBody,
                        $this->normalizeCssColor($probes['body']),
                        "Plan D.2 matrix [{$slug} × {$skin}]: body.color expected "
                        . "'{$expectedBody}' (--mw-body-color from pack) but got "
                        . "'{$probes['body']}'. A hardcoded color in this skin or "
                        . 'global SCSS is overriding the palette variable for body '
                        . 'text — this exact (palette, skin) pair regressed.'
                    );

                    if ($probes['heading'] !== '') {
                        $this->assertSame(
                            $expectedHeading,
                            $this->normalizeCssColor($probes['heading']),
                            "Plan D.2 matrix [{$slug} × {$skin}]: heading color "
                            . "expected '{$expectedHeading}' (--mw-heading-color "
                            . "from pack) but got '{$probes['heading']}'. The skin's "
                            . 'heading rule is resolving to a non-palette color for '
                            . 'this specific pack.'
                        );
                    } else {
                        fwrite(
                            STDERR,
                            "\n[layout-matrix] {$slug} × {$skin}: no heading element in canvas DOM — "
                            . "skipping --mw-heading-color DOM check\n"
                        );
                    }

                    $isGradientPack = str_starts_with(
                        trim((string) $properties['--mw-btn-background-color']),
                        'linear-gradient'
                    );

                    if ($probes['button'] !== '' && !$isGradientPack) {
                        // Accept either the idle btn-background-color OR
                        // the btn-background-hover-color: in headless
                        // Chrome the cursor often lingers over the first
                        // visible button after a navigation, so a
                        // freshly-loaded canvas iframe may probe the
                        // button while it's in :hover state. Both values
                        // are pack-declared, so either resolution proves
                        // the palette landed on the button rule. A pack
                        // that is missing hover-color falls back to the
                        // idle expectation.
                        $expectedBtnBgHover = isset($properties['--mw-btn-background-hover-color'])
                            ? $this->normalizeCssColor((string) $properties['--mw-btn-background-hover-color'])
                            : $expectedBtnBg;
                        $actualBtn = $this->normalizeCssColor($probes['button']);
                        $this->assertContains(
                            $actualBtn,
                            [$expectedBtnBg, $expectedBtnBgHover],
                            "Plan D.2 matrix [{$slug} × {$skin}]: primary button "
                            . "background expected one of [{$expectedBtnBg} idle, "
                            . "{$expectedBtnBgHover} hover] (both pack-declared) "
                            . "but got '{$probes['button']}'. A skin- or module-"
                            . 'level override is winning over both palette '
                            . 'variables for this specific pack — neither idle '
                            . 'nor hover color resolves through the cascade.'
                        );
                    } elseif ($isGradientPack) {
                        // Gradient packs (golden-hour, robocop) ship
                        // their --mw-btn-background-color as a
                        // linear-gradient — that resolves to the
                        // button's `background-image`, not its
                        // `background-color`. The Chrome computed style
                        // for `background-color` is `rgba(0, 0, 0, 0)`
                        // for these. The :root contract assertion above
                        // already proves the gradient string lands on
                        // the documentElement, which is the canonical
                        // signal for these packs. Skip the per-DOM
                        // probe with a visible note so a regression
                        // that drops the gradient from :root surfaces
                        // upstream, not here.
                        fwrite(
                            STDERR,
                            "\n[layout-matrix] {$slug} × {$skin}: linear-gradient pack — "
                            . ":root contract verified, skipping DOM background-color "
                            . "probe (the gradient resolves to background-image, not "
                            . "background-color)\n"
                        );
                    } else {
                        fwrite(
                            STDERR,
                            "\n[layout-matrix] {$slug} × {$skin}: no .btn-primary element in canvas DOM — "
                            . "skipping --mw-btn-background-color DOM check\n"
                        );
                    }
                }
            }
        });
    }

    /**
     * Read computed colors for the three primary accents on the
     * canvas iframe's actual DOM. Returned values are `rgb(...)`
     * strings straight from `getComputedStyle`, or the empty
     * string if the canvas does not render that element. Same
     * shape the sibling LiveEditColorPaletteSkinMatrixTest uses
     * — kept in sync deliberately so a regression in the probe
     * surfaces in both tests, not just one.
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

                // Scope the button probe AWAY from header / footer /
                // top-bar wrappers — those use the per-palette
                // `--mw-top-header-button-*` family of vars, not the
                // section-content `--mw-btn-*` family. Some packs
                // (e.g. midnight-indigo) declare opposite values for
                // the two families, and the matrix test must read the
                // section-content button to verify the section-scoped
                // palette variable, not the header one.
                function notInHeaderOrFooter(el) {
                    return !el.closest(
                        'header, footer, nav.navbar, .top-header, '
                        + '.site-header, .navbar, .mw-header, .mw-footer'
                    );
                }
                var button = '';
                var btnNodes = doc.querySelectorAll(
                    '.btn-primary, .btn:not(.btn-secondary):not(.btn-outline):not(.btn-link):not(.btn-dark)'
                );
                for (var j = 0; j < btnNodes.length; j++) {
                    var b = btnNodes[j];
                    if (!isVisible(b)) continue;
                    if (!notInHeaderOrFooter(b)) continue;
                    var bg = (win.getComputedStyle(b).backgroundColor || '').trim();
                    // Some skins style their first .btn-primary with an
                    // outline-style transparent background even though
                    // the class doesn't include `btn-outline` — its
                    // background-color resolves to rgba(0, 0, 0, 0).
                    // That doesn't carry the palette signal we're
                    // probing for; skip it and look for the next
                    // visible non-transparent button instead.
                    if (bg === '' || bg === 'rgba(0, 0, 0, 0)' || bg === 'transparent') continue;
                    button = bg;
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
            'body' => (string) ($payload['body'] ?? ''),
            'heading' => (string) ($payload['heading'] ?? ''),
            'button' => (string) ($payload['button'] ?? ''),
        ];
    }
}
