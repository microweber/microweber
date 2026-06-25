<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-8f20b6 / AI-714 (High) — Add Layouts modal:
 * category filter missing entirely on mobile.
 *
 * Designer dispatch (DESIGN_AUDIT.md L2.5, per-ticket email
 * 2026-05-16T13:54): the Add Layouts modal ("Insert layout")
 * has a 17-item category rail on desktop. On mobile 390 px the
 * entire category filter is hidden (`.mw-le-layouts-dialog-col:
 * first-child { display: none }` in
 * `packages/frontend-assets/resources/assets/ui/css/index.css`
 * line 590) with no replacement. Mobile users could only flat-
 * scroll or free-text search.
 *
 * Fix (designer option C — bottom-sheet, recommended): keep the
 * desktop rail unchanged. Mobile gets a Filter trigger button
 * next to the search input that opens a bottom-sheet listing
 * all categories. Tapping a category selects AND closes the
 * sheet so the user lands back on the filtered grid in one
 * gesture.
 *
 * Note on the AI-685/AI-695 dependency: the designer spec
 * references a bottom-sheet primitive shipping with AI-685/
 * AI-695. Neither has shipped yet. This implementation is a
 * minimal inline bottom-sheet sufficient for AI-714's surface;
 * can be refactored onto the primitive when it lands.
 *
 * Two-surface implementation:
 *
 *   1. ListLayouts.vue
 *      - `data().mobileFilterOpen` state boolean.
 *      - `toggleMobileFilter()` / `closeMobileFilter()` /
 *        `filterCategoryFromMobile(cat)` methods.
 *      - New `<button class="mw-le-layouts-mobile-filter-
 *        trigger">` next to the search input with
 *        aria-expanded + aria-controls.
 *      - New `<div id="mw-le-layouts-mobile-filter-sheet"
 *        class="mw-le-layouts-mobile-filter-sheet" role="dialog"
 *        aria-modal="true">` containing a duplicate of the
 *        `<ul class="modules-list-categories">` list with
 *        active-state binding off the SAME `filterCategory`
 *        data property — keeps both surfaces in sync.
 *
 *   2. index.css
 *      - Trigger + sheet display: none by default; revealed
 *        only at `@media (max-width: 767px)`.
 *      - Trigger button — 44 px hit area, current filter as
 *        label, chevron rotates on aria-expanded="true".
 *      - Sheet — fixed inset:0, backdrop dim, panel slides
 *        from bottom with `transform: translateY(100% → 0)`
 *        over var(--t-fast).
 *      - Sheet list rows — 44 px min-height per WCAG 2.5.5;
 *        active row gets `--ese-accent-soft` bg + `--ese-accent`
 *        text + check-mark prefix.
 *      - `prefers-reduced-motion: reduce` disables animations.
 *      - Every var() carries a literal fallback.
 *
 * Done-when criteria (designer):
 *   ✓ Every category accessible within 2 taps at mobile 390 px
 *     (tap trigger → tap category = 2 taps).
 *   ✓ Active category visibly indicated in BOTH desktop and
 *     mobile (same `.active` class binding off `filterCategory`).
 *   ✓ Mobile content area not reduced by inline categories (the
 *     trigger is one row above search; bottom-sheet is overlay).
 */
class LayoutsModal8f20b6AI714MobileFilterContractTest extends TestCase
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
    // Group A — Vue state + methods
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function vue_data_includes_mobile_filter_open_state(): void
    {
        $this->assertMatchesRegularExpression(
            '/mobileFilterOpen:\s*false/',
            $this->vue,
            '`data().mobileFilterOpen` must initialise to false — the bottom-sheet starts closed.'
        );
    }

    #[Test]
    public function vue_has_toggle_close_and_select_methods(): void
    {
        foreach ([
            'toggleMobileFilter\(\)\s*\{',
            'closeMobileFilter\(\)\s*\{',
            'filterCategoryFromMobile\(category\)\s*\{',
        ] as $methodRegex) {
            $this->assertMatchesRegularExpression(
                '/' . $methodRegex . '/',
                $this->vue,
                "ListLayouts.vue methods block must define `{$methodRegex}`."
            );
        }
    }

    #[Test]
    public function filter_from_mobile_helper_selects_and_closes(): void
    {
        // filterCategoryFromMobile(cat) must call BOTH
        // filterCategorySubmit(cat) AND closeMobileFilter() so the
        // 2-tap design (open → tap category) lands on the filtered
        // grid without an extra dismiss tap.
        $this->assertMatchesRegularExpression(
            '/filterCategoryFromMobile\(category\)\s*\{[\s\S]*?this\.filterCategorySubmit\(category\)[\s\S]*?this\.closeMobileFilter\(\)/',
            $this->vue,
            'filterCategoryFromMobile must call this.filterCategorySubmit(category) AND this.closeMobileFilter() so tap-category collapses the sheet immediately.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Trigger button
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function trigger_button_carries_aria_expanded(): void
    {
        $this->assertMatchesRegularExpression(
            '/class="mw-le-layouts-mobile-filter-trigger"[\s\S]{0,500}:aria-expanded="mobileFilterOpen\s*\?\s*[\'"]true[\'"]\s*:\s*[\'"]false[\'"]"/',
            $this->vue,
            'Mobile filter trigger button must bind :aria-expanded to mobileFilterOpen state.'
        );
    }

    #[Test]
    public function trigger_button_links_to_sheet_via_aria_controls(): void
    {
        $this->assertMatchesRegularExpression(
            '/class="mw-le-layouts-mobile-filter-trigger"[\s\S]{0,500}aria-controls="mw-le-layouts-mobile-filter-sheet"/',
            $this->vue,
            'Mobile filter trigger button must carry aria-controls="mw-le-layouts-mobile-filter-sheet" so AT users know which surface opens.'
        );
    }

    #[Test]
    public function trigger_button_shows_current_filter_as_label(): void
    {
        // Label reads `{filterCategory || "All categories"}` so
        // sighted users see the current filter on the trigger
        // itself — done-when "active category visibly indicated".
        $this->assertMatchesRegularExpression(
            "/mw-le-layouts-mobile-filter-trigger-label[\\s\\S]{0,300}filterCategory\\s*\\|\\|\\s*\\\$lang\\([\"']All categories[\"']/",
            $this->vue,
            "Trigger label must read `{filterCategory || \$lang('All categories')}` so the current filter is visible on the button itself."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Bottom-sheet structure
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function sheet_has_role_dialog_and_aria_modal(): void
    {
        $this->assertMatchesRegularExpression(
            '/id="mw-le-layouts-mobile-filter-sheet"[\s\S]{0,200}role="dialog"[\s\S]{0,200}aria-modal="true"/',
            $this->vue,
            'Bottom-sheet must carry role="dialog" + aria-modal="true" so AT users know it traps focus context.'
        );
    }

    #[Test]
    public function sheet_only_renders_when_open(): void
    {
        $this->assertMatchesRegularExpression(
            '/v-if="mobileFilterOpen"[\s\S]{0,500}id="mw-le-layouts-mobile-filter-sheet"/',
            $this->vue,
            'Sheet must use v-if="mobileFilterOpen" so it isn\'t in the DOM when closed (no escape-trap accidents on desktop).'
        );
    }

    #[Test]
    public function sheet_backdrop_click_dismisses(): void
    {
        $this->assertMatchesRegularExpression(
            '/mw-le-layouts-mobile-filter-sheet__backdrop[\s\S]{0,300}v-on:click="closeMobileFilter"/',
            $this->vue,
            'Backdrop click must dismiss the sheet via closeMobileFilter (canonical bottom-sheet UX).'
        );
    }

    #[Test]
    public function sheet_esc_key_dismisses(): void
    {
        $this->assertMatchesRegularExpression(
            '/v-on:keydown\.esc(?:\.prevent)?="closeMobileFilter"/',
            $this->vue,
            'Sheet must close on ESC keydown (WCAG 2.1.2 keyboard dismiss).'
        );
    }

    #[Test]
    public function sheet_list_categories_call_mobile_select_helper(): void
    {
        // The sheet's category <li>s MUST call
        // filterCategoryFromMobile (which selects + closes), NOT
        // filterCategorySubmit (which would leave the sheet open).
        $this->assertMatchesRegularExpression(
            '/mw-le-layouts-mobile-filter-sheet__list[\s\S]{0,2000}v-on:click="filterCategoryFromMobile\(/',
            $this->vue,
            'Bottom-sheet category rows must call filterCategoryFromMobile (NOT filterCategorySubmit) so tapping closes the sheet too.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — CSS responsive shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function trigger_and_sheet_hidden_by_default(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-mobile-filter-trigger\s*\{[^}]*display:\s*none/s',
            $this->css,
            'Trigger must default to display: none (revealed only inside the @media block).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-mobile-filter-sheet\s*\{[^}]*display:\s*none/s',
            $this->css,
            'Sheet wrapper must default to display: none (revealed only inside the @media block).'
        );
    }

    #[Test]
    public function trigger_and_sheet_revealed_at_767px_breakpoint(): void
    {
        // Both must appear inside @media (max-width: 767px).
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*767px\s*\)\s*\{[\s\S]*?\.mw-le-layouts-mobile-filter-trigger\s*\{[^}]*display:\s*inline-flex/',
            $this->css,
            'Trigger must be revealed inside @media (max-width: 767px) — matches the rail-hide breakpoint.'
        );
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*767px\s*\)\s*\{[\s\S]*?\.mw-le-layouts-mobile-filter-sheet\s*\{[^}]*display:\s*block/',
            $this->css,
            'Sheet must be revealed inside @media (max-width: 767px).'
        );
    }

    #[Test]
    public function trigger_meets_44px_touch_target_floor(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-mobile-filter-trigger\s*\{[^}]*min-height:\s*44px/s',
            $this->css,
            'Trigger button must have min-height: 44px per WCAG 2.5.5.'
        );
    }

    #[Test]
    public function sheet_list_rows_meet_44px_touch_target_floor(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-mobile-filter-sheet__list\s+li\s*\{[^}]*min-height:\s*44px/s',
            $this->css,
            'Bottom-sheet category rows must have min-height: 44px per WCAG 2.5.5.'
        );
    }

    #[Test]
    public function sheet_panel_slides_up_with_translateY_keyframes(): void
    {
        $this->assertMatchesRegularExpression(
            '/@keyframes\s+mw-le-layouts-mobile-filter-sheet-slide\s*\{[^}]*from\s*\{\s*transform:\s*translateY\(100%\)/s',
            $this->css,
            'Sheet panel must slide up from translateY(100%) → 0 — canonical bottom-sheet motion.'
        );
    }

    #[Test]
    public function active_category_in_sheet_uses_accent_soft_bg(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-mobile-filter-sheet__list\s+li\.active\s*\{[^}]*background-color:\s*var\(--ese-accent-soft/s',
            $this->css,
            'Active category row in the sheet must use --ese-accent-soft bg so the active filter is visibly indicated (done-when).'
        );
    }

    #[Test]
    public function reduced_motion_disables_sheet_animations(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*prefers-reduced-motion:\s*reduce\s*\)\s*\{[\s\S]*?\.mw-le-layouts-mobile-filter-sheet__panel\s*\{[^}]*animation:\s*none/s',
            $this->css,
            'prefers-reduced-motion: reduce must disable the sheet slide-in animation.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — Markers + token-fallback hygiene
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString('task-2026-05-16-8f20b6', $this->vue);
        $this->assertStringContainsString('task-2026-05-16-8f20b6', $this->css);
    }

    #[Test]
    public function ai714_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString('AI-714', $this->vue);
        $this->assertStringContainsString('AI-714', $this->css);
    }

    #[Test]
    public function css_tokens_carry_literal_fallbacks(): void
    {
        // Slice past the AI-714 docblock close `*/` to inspect
        // CSS rule bodies only (LESSONS selector-self-match
        // guard, 6th session-occurrence — pattern fully
        // internalised).
        $marker = strpos($this->css, 'AI-714 — Add Layouts modal mobile category filter');
        $this->assertNotFalse($marker, 'AI-714 task marker must be present in index.css.');
        $docblockEnd = strpos($this->css, '*/', $marker);
        $this->assertNotFalse($docblockEnd);
        $slice = substr($this->css, $docblockEnd + 2);

        $tokens = [
            '--font-control'      => '13px',
            '--font-section'      => '15px',
            '--space-xs'          => '6px',
            '--space-sm'          => '8px',
            '--space-md'          => '13px',
            // v2.0.20 restyle: small radius is now 4px (was 6px).
            '--radius-sm'         => '4px',
            '--radius-md'         => '8px',
            '--ese-surface'       => '#ffffff',
            '--ese-text'          => '#111827',
            '--ese-text-muted'    => '#6b7280',
            '--ese-accent'        => '#0d6efd',
            '--ese-accent-soft'   => 'rgba(13, 110, 253, 0.12)',
            '--ese-surface-hover' => 'rgba(0, 0, 0, 0.04)',
            '--ese-border'        => 'rgba(0, 0, 0, 0.08)',
            '--t-fast'            => '120ms',
        ];
        foreach ($tokens as $token => $fallback) {
            $this->assertMatchesRegularExpression(
                '/var\(' . preg_quote($token, '/') . ',\s*[^)]*' . preg_quote($fallback, '/') . '/',
                $slice,
                "Token {$token} must be consumed as var({$token}, <literal {$fallback}>) in the AI-714 slice."
            );
        }
    }
}
