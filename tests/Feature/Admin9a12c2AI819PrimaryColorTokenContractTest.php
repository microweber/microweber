<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-9a12c2 / AI-819 — Filament `fi-color-primary`
 * design token must resolve to the Microweber brand blue (#0d6efd
 * per AI-209 / AI-737 / AI-794 primary-CTA lineage) instead of the
 * Filament bundled default Tailwind blue (#4299e1).
 *
 * Root cause:
 *   Filament v5's `ColorManager` converts `->colors([...])` values
 *   through `Color::convertToOklch()` and emits CSS variables in
 *   OKLCH format. The project's `tailwind.config.js` still maps
 *   Tailwind utilities via `rgba(var(--primary-500), <alpha-value>)`
 *   — a comma-separated R,G,B triplet pattern. `rgba(oklch(...), 1)`
 *   is invalid CSS and falls back to the bundled Tailwind blue.
 *
 * Fix:
 *   Add a `:root` block to `microweber-theme-v3.scss` that emits the
 *   brand-anchored `--primary-XX` variables in the R,G,B triplet
 *   format the project's tailwind config expects. Values mirror
 *   `MwColors::Blue` verbatim (#e7f1ff → #010a19, anchored at
 *   #0d6efd on slot 500). Loads AFTER Filament's runtime emit so
 *   the explicit override wins the cascade.
 *
 * Pairs with AI-816 (task-2026-05-17-2b1020) — that ship swapped
 * Save CTAs from `->color('success')` to `->color('primary')`. Without
 * AI-819 also shipping, those CTAs would render at the wrong blue.
 *
 * AI-819a follow-up (documented inline in microweber-theme-v3.scss):
 *   the `$mw-accent: #4299e1` SCSS literal (line ~37) propagates the
 *   Tailwind-blue divergence to ~25 link / focus / checkbox surfaces.
 *   Re-anchoring it to #0d6efd should be a separate slice because
 *   $mw-accent participates in the MW v2 link-color contract and
 *   needs designer sign-off.
 *
 * Slice B not extended this slice: success / warning / danger / info
 * map to Filament's stock Color::Emerald / Amber / Rose / Sky which
 * carry brand-agnostic semantic-state coloring. No divergence from
 * project intent — left untouched.
 */
class Admin9a12c2AI819PrimaryColorTokenContractTest extends TestCase
{
    private function fileContents(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    public static function expectedPrimaryTokenValuesProvider(): array
    {
        // Verbatim from MwColors::Blue (admin Filament panel provider
        // already passes this array via `->colors(['primary' =>
        // MwColors::Blue])`). The :root override emits the same RGB
        // triplets in CSS-variable form so Tailwind v3 utilities
        // resolve correctly.
        return [
            'shade 50'  => ['--primary-50',  '231, 241, 255'],
            'shade 100' => ['--primary-100', '207, 226, 255'],
            'shade 200' => ['--primary-200', '158, 197, 254'],
            'shade 300' => ['--primary-300', '110, 168, 254'],
            'shade 400' => ['--primary-400', '61, 139, 253'],
            'shade 500' => ['--primary-500', '13, 110, 253'],
            'shade 600' => ['--primary-600', '10, 88, 202'],
            'shade 700' => ['--primary-700', '8, 66, 152'],
            'shade 800' => ['--primary-800', '5, 44, 101'],
            'shade 900' => ['--primary-900', '3, 22, 51'],
            'shade 950' => ['--primary-950', '1, 10, 25'],
        ];
    }

    #[Test]
    #[DataProvider('expectedPrimaryTokenValuesProvider')]
    public function source_scss_emits_brand_anchored_primary_token(string $tokenName, string $expectedRgbTriplet): void
    {
        $source = $this->fileContents('packages/microweber-filament-theme/resources/assets/css/tokens/colors.css');

        $this->assertStringContainsString(
            "{$tokenName}: {$expectedRgbTriplet};",
            $source,
            "AI-819: SCSS source must emit `{$tokenName}: {$expectedRgbTriplet};` so Filament `bg-primary-N` utilities resolve to the Microweber brand blue (#0d6efd anchored at slot 500)."
        );
    }

    #[Test]
    public function source_carries_ai819_marker_anchored_to_the_primary_root_block(): void
    {
        $source = $this->fileContents('packages/microweber-filament-theme/resources/assets/css/tokens/colors.css');

        $this->assertStringContainsString(
            'task-2026-05-17-9a12c2 / AI-819',
            $source,
            'AI-819 task-id marker must sit alongside the :root override block so a future audit can grep across surfaces (LESSONS per-task source-comment marker).'
        );

        $markerPos = strpos($source, 'task-2026-05-17-9a12c2 / AI-819');
        $this->assertNotFalse($markerPos);

        // The :root block must follow the marker (forward slice).
        $slice = substr($source, $markerPos, 4000);
        $this->assertStringContainsString(
            ':root {',
            $slice,
            'AI-819: marker must immediately precede the :root block emitting the --primary-XX overrides.'
        );
        $this->assertStringContainsString(
            '--primary-500: 13, 110, 253;',
            $slice,
            'AI-819: the slot-500 anchor must be brand-blue (#0d6efd).'
        );
    }

    #[Test]
    public function admin_panel_provider_still_wires_primary_to_mw_colors_blue(): void
    {
        // Defense-in-depth: even though the SCSS :root override is the
        // authoritative source post-AI-819, the PHP-side panel-provider
        // assignment must stay correct so any code path reading
        // MwColors::Blue (e.g. server-rendered Filament admin views with
        // inline color emission, future ColorManager-version upgrades)
        // continues to receive the brand palette.
        $source = $this->fileContents('src/MicroweberPackages/Admin/Filament/FilamentAdminPanelProvider.php');

        $this->assertMatchesRegularExpression(
            "/->colors\\(\\s*\\[[^\\]]*'primary'\\s*=>\\s*MwColors::Blue/s",
            $source,
            'AI-819: admin panel provider must still wire primary to MwColors::Blue (the override is belt-and-braces, not a replacement).'
        );

        $mwColors = $this->fileContents('src/MicroweberPackages/Admin/Filament/MwColors.php');
        $this->assertStringContainsString(
            "500 => '13, 110, 253'",
            $mwColors,
            'AI-819: MwColors::Blue[500] must stay anchored at brand `13, 110, 253` (#0d6efd).'
        );
    }

    #[Test]
    public function built_css_bundle_contains_the_primary_500_brand_override(): void
    {
        // Runtime probe: the built bundle that ships to /admin must
        // carry the override (sr-only equivalent — pin-in-built-bundle
        // pattern from AI-697-CHANGE / AI-771).
        $relativePath = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $absolute = base_path($relativePath);

        if (!file_exists($absolute)) {
            $this->markTestSkipped("Built bundle absent: {$relativePath}. Run `cd packages/microweber-filament-theme && npm run build` to surface the AI-819 override.");
        }

        $bundle = (string) file_get_contents($absolute);

        $this->assertStringContainsString(
            '--primary-500: 13, 110, 253',
            $bundle,
            'AI-819 runtime probe: built bundle must ship the brand-blue --primary-500 override. Re-run the Webpack build after editing microweber-theme-v3.scss.'
        );
        $this->assertStringContainsString(
            '--primary-50: 231, 241, 255',
            $bundle,
            'AI-819 runtime probe: built bundle must ship the full 50-950 ladder.'
        );
        $this->assertStringContainsString(
            '--primary-950: 1, 10, 25',
            $bundle,
            'AI-819 runtime probe: built bundle must ship the full 50-950 ladder.'
        );
    }

    #[Test]
    public function ai819a_followup_documented_in_source(): void
    {
        // Surface the $mw-accent: #4299e1 follow-up so a future
        // audit grepping `AI-819a` finds the deferred surface.
        $source = $this->fileContents('packages/microweber-filament-theme/resources/assets/css/tokens/colors.css');

        $this->assertStringContainsString(
            'AI-819a',
            $source,
            'AI-819a follow-up must be flagged inline in microweber-theme-v3.scss so the $mw-accent re-anchor is on record.'
        );
        $this->assertStringContainsString(
            '$mw-accent',
            $source,
            'AI-819a docblock must reference $mw-accent by name so a future audit can locate the SCSS literal.'
        );
    }

    #[Test]
    public function tailwind_config_consumer_pattern_unchanged(): void
    {
        // The override only WORKS because tailwind.config.js still
        // resolves `bg-primary-N` via `rgba(var(--primary-N),
        // <alpha-value>)`. If a future refactor switches the config
        // to space-separated triplets or OKLCH, the AI-819 override
        // breaks silently. Pin the consumer pattern as a regression
        // guard.
        $config = $this->fileContents('packages/microweber-filament-theme/tailwind.config.js');

        $this->assertStringContainsString(
            "'rgba(var(--primary-500), <alpha-value>)'",
            $config,
            'AI-819: tailwind.config.js must keep consuming --primary-500 via `rgba(var(--primary-500), <alpha-value>)`. A switch to space-separated triplets or OKLCH would silently break the AI-819 override.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group F — AI-819 CHANGE absorption (task-2026-05-17-68e111)
    //
    // Designer's verify-before-accept on the original AI-819 ship (commit
    // 32097ab25d) found 2 hardcoded `!important` rules in the SAME bundle
    // that bypassed the `:root` token override:
    //
    //   :775 (light) — .fi-btn.fi-color-primary:not(.admin-toolbar-buttons)
    //                  background-color: #182433 !important (MW v2 btn-dark)
    //   :2904 (dark) — .fi-btn.fi-color-primary:not(.admin-toolbar-add):not(.admin-toolbar-live-edit)
    //                  background-color: #4299e1 !important (pre-AI-819 Tailwind)
    //
    // Stage-2 sub-variant: token-override + downstream !important hardcode =
    // silent override-of-override. Codified post-AI-819 CHANGE absorption.
    //
    // Selector-self-match guard UNIFORMITY (post-task-7aa48a) — strip
    // comments before any absence assertions.
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai819_change_light_mode_primary_button_uses_brand_token(): void
    {
        $scss = $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/support/button/theme.css')
            . $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/dark.css')
            . $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/panels/topbar/theme.css');
        // Strip block + line comments so the docblock prose mentioning
        // legacy #182433 doesn't false-fail the negative regression-guard.
        $exec = preg_replace('~/\*[\s\S]*?\*/~', '', $scss);
        $exec = preg_replace('~//[^\n]*~', '', $exec);

        // Selector corrected to match dark-mode rule + excludes BOTH
        // toolbar variants (admin-toolbar-add + admin-toolbar-live-edit).
        $this->assertMatchesRegularExpression(
            '~\.fi-btn\.fi-color-primary:not\(\.admin-toolbar-add\):not\(\.admin-toolbar-live-edit\)\s*\{[^}]*background-color:\s*rgba\(var\(--primary-500\),\s*1\)\s*!important~',
            $exec,
            'AI-819 CHANGE: light-mode .fi-btn.fi-color-primary must consume rgba(var(--primary-500), 1) !important (NOT #182433) so the :root token override flows through.'
        );
        $this->assertStringContainsString(
            'border: 1px solid rgba(var(--primary-600), 1) !important',
            $exec,
            'AI-819 CHANGE: light-mode border must consume --primary-600 (NOT #1f2e41).'
        );
    }

    #[Test]
    public function ai819_change_dark_mode_primary_button_uses_brand_token(): void
    {
        $scss = $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/support/button/theme.css')
            . $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/dark.css')
            . $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/panels/topbar/theme.css');
        $exec = preg_replace('~/\*[\s\S]*?\*/~', '', $scss);
        $exec = preg_replace('~//[^\n]*~', '', $exec);

        // The dark-mode rule lives inside a `.dark { ... }` wrapper.
        $darkPos = strpos($exec, '.dark');
        $this->assertNotFalse($darkPos, 'AI-819 CHANGE: .dark wrapper must exist for dark-mode primary rule.');
        $darkSlice = substr($exec, $darkPos);

        $this->assertMatchesRegularExpression(
            '~\.fi-btn\.fi-color-primary:not\(\.admin-toolbar-add\):not\(\.admin-toolbar-live-edit\)\s*\{[^}]*background-color:\s*rgba\(var\(--primary-500\),\s*1\)\s*!important~',
            $darkSlice,
            'AI-819 CHANGE: dark-mode .fi-btn.fi-color-primary must consume rgba(var(--primary-500), 1) !important (NOT #4299e1).'
        );
    }

    #[Test]
    public function ai819_change_hover_uses_primary_600_token(): void
    {
        $scss = $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/support/button/theme.css')
            . $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/dark.css')
            . $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/panels/topbar/theme.css');
        $exec = preg_replace('~/\*[\s\S]*?\*/~', '', $scss);
        $exec = preg_replace('~//[^\n]*~', '', $exec);

        // :hover background-color must use --primary-600 in BOTH the
        // light-mode rule (was #1f2e41) AND the dark-mode rule (was #3182ce).
        // Expect at least 2 occurrences in executable source.
        $occurrences = preg_match_all(
            '~&?:hover\s*\{[^}]*background-color:\s*rgba\(var\(--primary-600\),\s*1\)\s*!important~',
            $exec,
            $m
        );
        $this->assertGreaterThanOrEqual(
            2,
            $occurrences,
            'AI-819 CHANGE: both light-mode + dark-mode :hover must consume rgba(var(--primary-600), 1) !important. Found ' . $occurrences . ' occurrences.'
        );
    }

    #[Test]
    public function ai819_change_legacy_hardcoded_literals_removed_from_primary_rules(): void
    {
        $scss = $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/support/button/theme.css')
            . $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/dark.css')
            . $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/panels/topbar/theme.css');
        $exec = preg_replace('~/\*[\s\S]*?\*/~', '', $scss);
        $exec = preg_replace('~//[^\n]*~', '', $exec);

        // Slice each .fi-color-primary :not(...) rule and assert its body
        // does NOT contain the pre-CHANGE literals. Use preg_match_all to
        // walk every occurrence of the corrected selector.
        preg_match_all(
            '~\.fi-btn\.fi-color-primary:not\(\.admin-toolbar-add\):not\(\.admin-toolbar-live-edit\)\s*\{[^}]*\}~',
            $exec,
            $matches
        );
        $this->assertGreaterThanOrEqual(
            2,
            count($matches[0]),
            'AI-819 CHANGE: expect at least 2 corrected-selector rules (light + dark).'
        );

        foreach ($matches[0] as $ruleBody) {
            $this->assertStringNotContainsString(
                '#182433',
                $ruleBody,
                'AI-819 CHANGE regression-guard: rule body must NOT carry literal #182433 (MW v2 btn-dark).'
            );
            $this->assertStringNotContainsString(
                '#4299e1',
                $ruleBody,
                'AI-819 CHANGE regression-guard: rule body must NOT carry literal #4299e1 (pre-AI-819 Tailwind blue).'
            );
        }
    }

    #[Test]
    public function ai819_change_obsolete_admin_toolbar_buttons_selector_removed(): void
    {
        $scss = $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/support/button/theme.css')
            . $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/dark.css')
            . $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/panels/topbar/theme.css');
        $exec = preg_replace('~/\*[\s\S]*?\*/~', '', $scss);
        $exec = preg_replace('~//[^\n]*~', '', $exec);

        // The pre-CHANGE selector `:not(.admin-toolbar-buttons)` was
        // INVERTED — it didn't match the live-edit toolbar SAVE class
        // (`admin-toolbar-live-edit`) so the v2-dark style hijacked
        // every primary CTA instead of only the toolbar. Regression-guard:
        // the corrected selector replaces it entirely. The bare
        // `:not(.admin-toolbar-buttons)` pattern on .fi-color-primary
        // should no longer appear.
        $this->assertDoesNotMatchRegularExpression(
            '~\.fi-btn\.fi-color-primary:not\(\.admin-toolbar-buttons\)\s*\{~',
            $exec,
            'AI-819 CHANGE: legacy single-:not selector must be replaced by the corrected :not(.admin-toolbar-add):not(.admin-toolbar-live-edit) shape.'
        );
    }

    #[Test]
    public function ai819_change_ai699_toolbar_pills_unaffected(): void
    {
        // Regression-guard for the AI-699 black-pill SAVE button +
        // AI-704 toolbar +Add. Both have their own explicit rules at
        // lines 210 (.admin-toolbar-add) + 237 (.admin-toolbar-live-edit).
        // The corrected AI-819 CHANGE selector EXCLUDES both via :not(),
        // so the brand-blue re-anchor must not touch the toolbar.
        $scss = $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/support/button/theme.css')
            . $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/dark.css')
            . $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/panels/topbar/theme.css');
        $exec = preg_replace('~/\*[\s\S]*?\*/~', '', $scss);
        $exec = preg_replace('~//[^\n]*~', '', $exec);

        // .admin-toolbar-live-edit.fi-color-primary rule still present
        // and still light-green (per AI-704). Asserts that AI-819 CHANGE
        // didn't accidentally rewrite the wrong rule.
        $this->assertMatchesRegularExpression(
            '~\.fi-btn\.admin-toolbar-live-edit\.fi-color-primary\s*[,{][\s\S]*?background-color:\s*#e2f9e6~',
            $exec,
            'AI-704 regression-guard: admin-toolbar-live-edit SAVE pill must keep its MW v2 light-green bg #e2f9e6 (AI-699 black-pill is rendered by Vue Toolbar SaveButton.vue, not this rule).'
        );

        // .admin-toolbar-add.fi-color-primary rule still light-blue
        $this->assertMatchesRegularExpression(
            '~\.fi-btn\.admin-toolbar-add\.fi-color-primary\s*[,{][\s\S]*?background-color:\s*#e1edf8~',
            $exec,
            'AI-704 regression-guard: admin-toolbar-add +Add pill must keep its MW v2 light-blue bg #e1edf8.'
        );
    }

    #[Test]
    public function ai819_change_task_id_marker_in_source(): void
    {
        $scss = $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/support/button/theme.css')
            . $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/dark.css')
            . $this->fileContents('packages/microweber-filament-theme/resources/assets/css/filament/panels/topbar/theme.css');
        $this->assertStringContainsString(
            'task-2026-05-17-68e111',
            $scss,
            'AI-819 CHANGE absorption ship must embed task-id marker for grep-discoverability per LESSONS.'
        );
    }

    #[Test]
    public function ai819_change_served_bundle_carries_corrected_selector(): void
    {
        // Tier-2 served-bundle probe — after Webpack rebuild, the served
        // CSS must carry the corrected selector + token-anchored values.
        $bundlePath = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $bundle = $this->fileContents($bundlePath);

        $this->assertStringContainsString(
            '.fi-btn.fi-color-primary:not(.admin-toolbar-add):not(.admin-toolbar-live-edit)',
            $bundle,
            'AI-819 CHANGE Tier-2: served bundle must carry the corrected selector. Bundle stale? Re-run `cd packages/microweber-filament-theme && npm run build`.'
        );

        // Both outer rules (light + dark) must consume --primary-500
        $primary500Count = substr_count($bundle, 'rgba(var(--primary-500), 1) !important');
        $this->assertGreaterThanOrEqual(
            2,
            $primary500Count,
            'AI-819 CHANGE Tier-2: served bundle must carry at least 2 occurrences of `rgba(var(--primary-500), 1) !important` (light outer + dark outer). Found ' . $primary500Count . '.'
        );

        // Hover + light-border must consume --primary-600
        $primary600Count = substr_count($bundle, 'rgba(var(--primary-600), 1) !important');
        $this->assertGreaterThanOrEqual(
            3,
            $primary600Count,
            'AI-819 CHANGE Tier-2: served bundle must carry at least 3 occurrences of `rgba(var(--primary-600), 1) !important` (light border + light hover + dark hover). Found ' . $primary600Count . '.'
        );
    }
}
