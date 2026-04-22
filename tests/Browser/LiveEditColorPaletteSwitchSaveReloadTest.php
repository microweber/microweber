<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\ColorPaletteFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansColorPaletteTestFixtures;
use Tests\Browser\Traits\LiveEditColorPaletteTrait;
use Tests\DuskTestCase;

/**
 * Phase-5 palette-switch save-reload round-trip.
 *
 * Extends the in-memory switch-bleed test ({@see LiveEditColorPaletteSwitchNoBleedTest})
 * across the save pipeline:
 *
 *   1. Apply palette A → Save       → reload the live-edit page
 *   2. Assert A's variables are the ones paint on `:root`
 *      AND present in the persisted `options.template_css` row
 *      (option_group = `template_Bootstrap`, the row
 *      {@see \MicroweberPackages\Template\Adapters\TemplateLiveEditCss::saveLiveEditCssContent()}
 *      writes on every palette apply-save).
 *   3. Apply palette B → Save       → reload the live-edit page
 *   4. Assert B's variables now paint on `:root` AND are present in the
 *      same options row, and — the anti-stale contract — A's distinctive
 *      values are *no longer* present in the row or on `:root`.
 *
 * What this catches that earlier Phase-5 tests do not:
 *   - In-memory bleed tests stop after the iframe paints; a save-side
 *     regression (e.g. `saveLiveEditCssContent` writing a union instead
 *     of a replace, a cache-invalidation miss on
 *     `liveEditCssAdapter`, or the `options.template_css` row being
 *     updated but the custom-css file not being rewritten) would slip
 *     through until a user hits public render. This asserts the
 *     *stored* state directly, so we catch those failures at the DB.
 *   - Public-render parity ({@see LiveEditColorPalettePublicRenderTest})
 *     exercises single-apply only. A stale-A-row regression would pass
 *     that test (A still renders fine) but fail here (after B, A must
 *     be gone).
 *
 * Why apple-shine → neon-night:
 *   - The two packs disagree on ~22 of their 25 shared `--mw-*` keys
 *     (see sibling SwitchNoBleedTest for why), so the "A's distinctive
 *     values are gone" assertion has maximal signal. A handful of keys
 *     happen to share values (btn text = #ffffff in both, etc.) — we
 *     sidestep those when computing A-only probes.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditColorPaletteSwitchSaveReloadTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansColorPaletteTestFixtures;
    use LiveEditColorPaletteTrait;

    private const PALETTE_A = 'apple-shine';
    private const PALETTE_B = 'neon-night';
    private const TEMPLATE = 'Bootstrap';
    private const CSS_OPTION_KEY = 'template_css';
    private const CSS_OPTION_GROUP = 'template_Bootstrap';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function save_then_reload_across_palette_switch_persists_only_b(): void
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

        $packA = $packs[self::PALETTE_A];
        $packB = $packs[self::PALETTE_B];

        // Probes are the subset of shared properties where A and B
        // disagree — only on these can we meaningfully assert "A's
        // value is gone after B". Discarding before the browser
        // session avoids flaky assertions on pack-agreement keys.
        $probes = $this->buildDisagreementProbes($packA['properties'], $packB['properties']);
        $this->assertNotEmpty(
            $probes,
            'apple-shine / neon-night must disagree on at least one '
            . 'shared property for the anti-stale contract to be testable'
        );

        $fixture = ColorPaletteFactory::make('save-reload-switch');

        $this->browse(function (Browser $browser) use ($fixture, $packA, $packB, $probes) {
            $this->loginAsAdmin($browser);

            // ---- Step 1: apply A → Save ----
            $this->openColorPaletteSidebar($browser, $fixture->pageId);
            $this->clickPalette($browser, self::PALETTE_A);
            $this->assertPaletteApplied($browser, $packA['properties']);
            $this->saveLiveEdit($browser);
            $browser->pause(1500);

            // ---- Step 2: reload and assert A is the persisted state ----
            $this->reopenLiveEdit($browser, $fixture->pageId);

            $rootAfterA = $this->snapshotRootCssVars($browser);
            $this->assertPersistedPaletteMatches(
                $packA['properties'],
                $rootAfterA,
                'A',
                $probes,
                // A was the only apply — B probes should NOT appear yet.
                null
            );

            // ---- Step 3: apply B → Save ----
            $this->clickPalette($browser, self::PALETTE_B);
            $this->assertPaletteApplied($browser, $packB['properties']);
            $this->saveLiveEdit($browser);
            $browser->pause(1500);

            // ---- Step 4: reload and assert B replaces A ----
            $this->reopenLiveEdit($browser, $fixture->pageId);

            $rootAfterB = $this->snapshotRootCssVars($browser);
            $this->assertPersistedPaletteMatches(
                $packB['properties'],
                $rootAfterB,
                'B',
                $probes,
                // A-era distinctive probes must no longer match on :root
                // after the B save+reload.
                'A_IS_GONE'
            );
        });
    }

    /**
     * Build [property => [aValue, bValue]] for every key that A and B
     * both declare AND disagree on (after color normalization). Packs
     * agreeing on a property can't prove a stale-A bleed.
     *
     * @param array<string,string> $a
     * @param array<string,string> $b
     * @return array<string, array{0: string, 1: string}>
     */
    private function buildDisagreementProbes(array $a, array $b): array
    {
        $probes = [];
        foreach ($b as $prop => $bVal) {
            if (!array_key_exists($prop, $a)) {
                continue;
            }
            $aNorm = $this->normalizeCssColor((string)$a[$prop]);
            $bNorm = $this->normalizeCssColor((string)$bVal);
            if ($aNorm === $bNorm) {
                continue;
            }
            $probes[$prop] = [(string)$a[$prop], (string)$bVal];
        }
        return $probes;
    }

    /**
     * Reload the live-edit page for a fixture so we observe exactly the
     * persisted state — no in-memory cssEditor layer. Uses the same
     * readiness contract as {@see openColorPaletteSidebar()}.
     */
    private function reopenLiveEdit(Browser $browser, int $pageId): void
    {
        $link = content_link($pageId);
        if (!$link) {
            throw new \RuntimeException(
                "reopenLiveEdit: content_link({$pageId}) returned empty"
            );
        }

        $browser->visit('/admin/live-edit?url=' . urlencode($link))->pause(5000);
        $browser->waitFor('iframe', 20)->pause(3000);

        for ($i = 0; $i < 30; $i++) {
            $ready = $browser->script(
                "return (typeof window.mw !== 'undefined'
                    && window.mw.top
                    && window.mw.top().app
                    && window.mw.top().app.canvas
                    && typeof window.mw.top().app.canvas.getDocument === 'function'
                    && window.mw.top().app.canvas.getDocument()
                    && window.mw.top().app.canvas.getDocument().documentElement
                ) ? 1 : 0;"
            );
            if (($ready[0] ?? 0) === 1) {
                return;
            }
            $browser->pause(500);
        }

        throw new \RuntimeException(
            'reopenLiveEdit: canvas iframe never became ready within 15s'
        );
    }

    /**
     * Assert the just-reloaded live-edit canvas matches the expected
     * pack's declared values on every key the pack defines, and that
     * the persisted options row ({$CSS_OPTION_GROUP}.{$CSS_OPTION_KEY})
     * contains the expected-pack's distinctive values. If $antiStaleTag
     * is 'A_IS_GONE', additionally assert A-era values are absent.
     *
     * @param array<string,string> $expected the pack we just saved
     * @param array<string,string> $rootSnapshot canvas-document computed vars
     * @param string $label 'A' or 'B' for error framing
     * @param array<string,array{0:string,1:string}> $probes A/B disagreement pairs
     * @param 'A_IS_GONE'|null $antiStaleTag
     */
    private function assertPersistedPaletteMatches(
        array $expected,
        array $rootSnapshot,
        string $label,
        array $probes,
        ?string $antiStaleTag
    ): void {
        // --- :root computed styles ---
        foreach ($expected as $prop => $expVal) {
            $this->assertArrayHasKey(
                $prop,
                $rootSnapshot,
                "After save+reload for palette {$label}, :root is missing '{$prop}' — "
                . 'the persisted custom-css did not re-hydrate the canvas'
            );
            $this->assertSame(
                $this->normalizeCssColor((string)$expVal),
                $this->normalizeCssColor((string)$rootSnapshot[$prop]),
                "After save+reload for palette {$label}, :root '{$prop}' expected "
                . "'{$expVal}' but canvas has '{$rootSnapshot[$prop]}'"
            );
        }

        // --- options.template_css row ---
        $stored = $this->readStoredCustomCss();
        $this->assertNotNull(
            $stored,
            'Expected options.template_css row in template_Bootstrap group to '
            . 'exist after a palette save, but none was found'
        );
        $this->assertNotSame(
            '',
            trim($stored),
            'options.template_css row exists but is empty — save pipeline '
            . 'persisted an empty CSS blob'
        );

        // For every disagreement probe, the stored CSS must carry the
        // *expected* pack's value on that key; for A_IS_GONE, the other
        // pack's value must NOT be present on that same key.
        foreach ($probes as $prop => [$aVal, $bVal]) {
            $wantVal = $label === 'A' ? $aVal : $bVal;
            $dontWantVal = $antiStaleTag === 'A_IS_GONE' ? $aVal : null;

            $this->assertTrue(
                $this->cssDeclarationPresent($stored, $prop, $wantVal),
                "After save for palette {$label}, stored template_css row must "
                . "contain the declaration '{$prop}: {$wantVal}' but it is absent. "
                . 'Stored CSS (trimmed): '
                . substr(preg_replace('/\s+/', ' ', $stored), 0, 400)
            );

            if ($dontWantVal !== null) {
                $this->assertFalse(
                    $this->cssDeclarationPresent($stored, $prop, $dontWantVal),
                    "After save for palette {$label}, stored template_css row "
                    . "still carries palette A's '{$prop}: {$dontWantVal}' — "
                    . 'save-reload bleed detected'
                );

                // :root must have dropped A's distinctive value too. We
                // compare against both color-normalized and raw forms to
                // handle browsers that echo rgb() back for hex inputs.
                $gotNorm = $this->normalizeCssColor((string)($rootSnapshot[$prop] ?? ''));
                $this->assertNotSame(
                    $this->normalizeCssColor($dontWantVal),
                    $gotNorm,
                    "After save+reload for palette {$label}, canvas :root "
                    . "'{$prop}' still matches palette A's value "
                    . "'{$dontWantVal}' — save-reload bleed detected"
                );
            }
        }
    }

    /**
     * Read the stored live-edit custom CSS for the Bootstrap template.
     * This is the same row {@see TemplateLiveEditCss::saveLiveEditCssContent()}
     * writes on every successful template_save_css endpoint call.
     *
     * We invalidate the cache first because the artisan-serve worker
     * memoizes option reads and may return the A-era row even right
     * after a B save if we queried through the cached repository.
     */
    private function readStoredCustomCss(): ?string
    {
        try {
            $app = app();
            if (isset($app->cache_manager) && method_exists($app->cache_manager, 'delete')) {
                $app->cache_manager->delete('options');
                $app->cache_manager->delete('options/' . self::CSS_OPTION_GROUP);
            }
            if (isset($app->option_repository) && method_exists($app->option_repository, 'clearCache')) {
                $app->option_repository->clearCache();
            }
        } catch (\Throwable) {
            // best-effort
        }

        $row = DB::table('options')
            ->where('option_key', self::CSS_OPTION_KEY)
            ->where('option_group', self::CSS_OPTION_GROUP)
            ->orderByDesc('id')
            ->first();

        if (!$row) {
            return null;
        }

        return (string)$row->option_value;
    }

    /**
     * Heuristic "is this declaration in the CSS text?" check. We match
     * `<property><optional space>:<optional space><value>` case-
     * insensitively. The CSS editor emits both hex and rgb forms
     * depending on pipeline; we probe both.
     */
    private function cssDeclarationPresent(string $css, string $property, string $value): bool
    {
        $haystack = strtolower($css);
        $prop = strtolower($property);
        $forms = array_unique([
            strtolower(trim($value)),
            $this->normalizeCssColor($value),
        ]);

        foreach ($forms as $needle) {
            if ($needle === '') {
                continue;
            }
            // Match "property:value" allowing any amount of whitespace.
            $pattern = '/'
                . preg_quote($prop, '/')
                . '\s*:\s*'
                . preg_quote($needle, '/')
                . '\s*[;}]/';
            if (preg_match($pattern, $haystack) === 1) {
                return true;
            }
        }

        return false;
    }
}
