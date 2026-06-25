<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-847083 / AI-715 (Medium) — Add Layouts desktop
 * polish: search promote + top alignment + selected-category echo.
 *
 * Designer dispatch (DESIGN_AUDIT.md / Drunk-Designer 5-heuristic
 * protocol): three failed heuristics on the Add Layouts desktop
 * surface.
 *
 *   1. Eye-flow test FAIL — search rendered as faint placeholder
 *      only; eye lands on thumbnail grid. Fix: promote
 *      `.modules-list-search-field` to the MwField visual
 *      contract — `--ese-surface-muted` bg, `--ese-border` 1px,
 *      `--radius-sm`, 44px height.
 *
 *   2. Edge-alignment scan FAIL — left rail starts at y≈17px,
 *      right pane search at y≈47px (30px misalignment). Fix:
 *      both columns' first content row aligns to `var(--space-md)`.
 *
 *   3. Section continuity FAIL — clicking "Header" category leaves
 *      no visible echo in the right pane. Active state in left
 *      rail is subtle blue underline only — easy to miss. Fix:
 *      a) right pane gets `.mw-le-layouts-result-header` showing
 *         `{Category} · {N} layouts` (--font-section name +
 *         --font-label count).
 *      b) active left-rail item gets `--ese-accent-soft` bg +
 *         `--ese-accent` text (matches MwToolButton toggle accent
 *         contract).
 *
 * Two-surface implementation:
 *
 *   1. ListLayouts.vue
 *      - New `.mw-le-layouts-result-header` block above the
 *        search input. Renders `{filterCategory || "All
 *        categories"} · {N}` with `aria-live="polite"` so screen
 *        readers announce changes when the user switches
 *        categories.
 *
 *   2. index.css
 *      - §1 Search field MwField visual contract (with focus
 *        ring using --ese-accent + --ese-accent-soft).
 *      - §2 Top-alignment override on `.modules-list-categories`
 *        py-5 (Bootstrap 1.25rem) → var(--space-md); matching
 *        padding-top on right-pane wrapper.
 *      - §3a Active left-rail category: --ese-accent-soft bg +
 *        --ese-accent text + --radius-sm rounding + 600 weight.
 *      - §3b Result-header typography: name in --font-section,
 *        separator `·` muted, count in --font-label uppercase.
 */
class LayoutsModal847083AI715DesktopPolishContractTest extends TestCase
{
    private string $vue;
    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vue = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Layouts/ListLayouts.vue'
        ));
        $this->css = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/css/index.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — §3b result-header Vue block
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function vue_renders_result_header_with_aria_live_polite(): void
    {
        // aria-live="polite" is required so screen readers
        // announce category switches without interrupting
        // current speech (matches WCAG 4.1.3 status-messages
        // pattern).
        $this->assertMatchesRegularExpression(
            '/<div\s+v-if="layoutsList\?\.categories\?\.length"\s+class="mw-le-layouts-result-header"\s+aria-live="polite"/',
            $this->vue,
            'Result-header must carry aria-live="polite" so screen-reader users hear category + count changes.'
        );
    }

    #[Test]
    public function vue_renders_category_name_with_default_fallback(): void
    {
        $this->assertMatchesRegularExpression(
            "/mw-le-layouts-result-header__name[\\s\\S]{0,200}filterCategory\\s*\\|\\|\\s*\\\$lang\\([\"']All categories[\"']\\)/",
            $this->vue,
            "Result-header name must render `{filterCategory || \$lang('All categories')}` so the empty filter shows All categories."
        );
    }

    #[Test]
    public function vue_renders_count_with_pluralisation(): void
    {
        $this->assertMatchesRegularExpression(
            "/mw-le-layouts-result-header__count[\\s\\S]{0,400}layoutsListFiltered\\?\\.length[\\s\\S]{0,400}layoutsListFiltered\\?\\.length\\s*===\\s*1[\\s\\S]{0,200}\\\$lang\\([\"']layout[\"']\\)[\\s\\S]{0,200}\\\$lang\\([\"']layouts[\"']\\)/",
            $this->vue,
            "Result-header count must pluralise based on layoutsListFiltered.length (1 → 'layout', else 'layouts')."
        );
    }

    #[Test]
    public function vue_separator_is_aria_hidden(): void
    {
        // The `·` separator is decorative; AT users should hear
        // the count as a continuation of the name, not as a
        // pronounced middle-dot character.
        $this->assertMatchesRegularExpression(
            '/mw-le-layouts-result-header__separator"\s+aria-hidden="true"/',
            $this->vue,
            'The `·` separator span must carry aria-hidden="true" — decorative only.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — §1 search field MwField visual contract
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function search_field_uses_ese_surface_muted_background(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.modules-list-search-field\s*\{[^}]*background-color:\s*var\(--ese-surface-muted/s',
            $this->css,
            'AI-715 §1: search field must use var(--ese-surface-muted) bg per MwField contract.'
        );
    }

    #[Test]
    public function search_field_has_44px_min_height(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.modules-list-search-field\s*\{[^}]*min-height:\s*44px/s',
            $this->css,
            'AI-715 §1: search field must have min-height: 44px (WCAG 2.5.5 + MwField spec).'
        );
    }

    #[Test]
    public function search_field_uses_ese_border_token(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.modules-list-search-field\s*\{[^}]*border:\s*1px\s+solid\s+var\(--ese-border/s',
            $this->css,
            'AI-715 §1: search field must use 1px var(--ese-border) per MwField contract.'
        );
    }

    #[Test]
    public function search_field_uses_radius_sm(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.modules-list-search-field\s*\{[^}]*border-radius:\s*var\(--radius-sm/s',
            $this->css,
            'AI-715 §1: search field must use var(--radius-sm) per MwField contract.'
        );
    }

    #[Test]
    public function search_field_focus_uses_accent_ring(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.modules-list-search-field:focus[\s\S]{0,400}border-color:\s*var\(--ese-accent[\s\S]{0,400}box-shadow:[\s\S]{0,200}var\(--ese-accent-soft/s',
            $this->css,
            'AI-715 §1: search field :focus must shift border to --ese-accent and add a --ese-accent-soft box-shadow ring.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — §2 top-alignment fix
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function categories_rail_top_padding_uses_space_md(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-dialog-col:first-child\s+\.modules-list-categories\s*\{[^}]*padding-top:\s*var\(--space-md,\s*13px\)\s*!important/s',
            $this->css,
            'AI-715 §2: left-rail categories must override Bootstrap py-5 with padding-top: var(--space-md) !important.'
        );
    }

    #[Test]
    public function right_pane_top_padding_matches_left(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-dialog-col:last-child\s*>\s*div:first-child\s*\{[^}]*padding-top:\s*var\(--space-md,\s*13px\)/s',
            $this->css,
            'AI-715 §2: right-pane first wrapper must match the left rail at var(--space-md) padding-top so both columns align.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — §3a active left-rail accent
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function active_category_li_uses_accent_soft_bg(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-dialog-col:first-child\s+\.modules-list-categories\s+li\.active[\s\S]{0,500}background-color:\s*var\(--ese-accent-soft/s',
            $this->css,
            'AI-715 §3a: active <li> in left rail must use var(--ese-accent-soft) bg per MwToolButton toggle accent contract.'
        );
    }

    #[Test]
    public function active_category_anchor_uses_accent_soft_bg(): void
    {
        // The v-for'd categories put `.active` on the inner <a>,
        // not the <li>. Both selector shapes must match.
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-dialog-col:first-child\s+\.modules-list-categories\s+li\s+a\.active[\s\S]{0,500}background-color:\s*var\(--ese-accent-soft/s',
            $this->css,
            'AI-715 §3a: active <a> inside <li> in left rail must also use --ese-accent-soft (the v-for shape carries .active on the anchor, not the li).'
        );
    }

    #[Test]
    public function active_category_uses_accent_text_colour(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-dialog-col:first-child\s+\.modules-list-categories\s+li\.active[\s\S]{0,500}color:\s*var\(--ese-accent/s',
            $this->css,
            'AI-715 §3a: active <li> must use var(--ese-accent) text colour.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — §3b result-header CSS typography
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function result_header_name_uses_font_section(): void
    {
        // The outer .mw-le-layouts-result-header sets the base
        // size to var(--font-section); the inner __name carries
        // weight 600 to stand out.
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-result-header\s*\{[^}]*font-size:\s*var\(--font-section/s',
            $this->css,
            'AI-715 §3b: result-header base font-size must be var(--font-section).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-result-header__name\s*\{[^}]*font-weight:\s*600/s',
            $this->css,
            'AI-715 §3b: result-header name must be font-weight 600.'
        );
    }

    #[Test]
    public function result_header_count_uses_font_label_metadata_typography(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-result-header__count\s*\{[^}]*font-size:\s*var\(--font-label[\s\S]{0,200}color:\s*var\(--ese-text-muted[\s\S]{0,200}text-transform:\s*uppercase[\s\S]{0,200}letter-spacing:\s*var\(--letter-label/s',
            $this->css,
            'AI-715 §3b: result-header count must use --font-label + --ese-text-muted + uppercase + --letter-label (metadata typography).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group F — Markers + token-fallback hygiene
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString('task-2026-05-16-847083', $this->vue);
        $this->assertStringContainsString('task-2026-05-16-847083', $this->css);
    }

    #[Test]
    public function ai715_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString('AI-715', $this->vue);
        $this->assertStringContainsString('AI-715', $this->css);
    }

    #[Test]
    public function css_tokens_carry_literal_fallbacks(): void
    {
        // Slice past the AI-715 docblock close `*/` to inspect
        // CSS rule bodies only (LESSONS selector-self-match guard,
        // 7th session-occurrence).
        $marker = strpos($this->css, 'AI-715 — Add Layouts desktop polish');
        $this->assertNotFalse($marker, 'AI-715 task marker must be present in index.css.');
        $docblockEnd = strpos($this->css, '*/', $marker);
        $this->assertNotFalse($docblockEnd);
        $slice = substr($this->css, $docblockEnd + 2);

        $tokens = [
            '--space-xs'          => '6px',
            '--space-sm'          => '8px',
            '--space-md'          => '13px',
            '--font-control'      => '13px',
            '--font-label'        => '11px',
            '--font-section'      => '15px',
            '--letter-label'      => '0.01em',
            // v2.0.20 restyle: small radius is now 4px (was 6px).
            '--radius-sm'         => '4px',
            '--ese-text'          => '#111827',
            '--ese-text-muted'    => '#6b7280',
            '--ese-surface'       => '#ffffff',
            '--ese-surface-muted' => 'rgba(0, 0, 0, 0.04)',
            '--ese-border'        => 'rgba(0, 0, 0, 0.08)',
            '--ese-accent'        => '#0d6efd',
            '--ese-accent-soft'   => 'rgba(13, 110, 253, 0.12)',
        ];
        foreach ($tokens as $token => $fallback) {
            $this->assertMatchesRegularExpression(
                '/var\(' . preg_quote($token, '/') . ',\s*[^)]*' . preg_quote($fallback, '/') . '/',
                $slice,
                "Token {$token} must be consumed as var({$token}, <literal {$fallback}>) in the AI-715 slice."
            );
        }
    }
}
