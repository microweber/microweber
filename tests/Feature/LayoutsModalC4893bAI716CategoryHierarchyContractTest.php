<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-c4893b / AI-716 (Low) — Add Layouts left-rail:
 * 17 categories need hierarchy (Featured / All).
 *
 * Designer dispatch (DESIGN_AUDIT.md, per-ticket email
 * 2026-05-16T13:54): 17 categories rendered as one flat list with
 * equal visual weight. Frequently-used (Content, Header, Features)
 * compete equally with rarely-needed (Misc, Titles, Text Block).
 *
 * Fix: three groups separated by var(--space-sm) + group header
 * in --font-label:
 *   1. (top, no header) All Categories — default active.
 *   2. Featured — Content / Header / Features / Hero /
 *      Call To Action / Gallery (6 highest-use).
 *   3. All categories — alphabetised, the remaining 11.
 *
 * Featured set MUST be configurable in code (NOT hardcoded in
 * template) so PM can re-weight later.
 *
 * Per spec "Ready-when: After AI-714 (so the same grouping
 * applies to mobile's bottom-sheet too)" — the same 3-group
 * structure is mirrored in both surfaces.
 *
 * Two-surface implementation:
 *
 *   1. ListLayouts.vue
 *      - `data().featuredCategoryNames` array (6 designer-
 *        curated names) — single source of truth, PM-editable.
 *      - `computed.featuredCategories` filters the data array
 *        to only categories actually present in
 *        `layoutsList.categories`, preserving spec order.
 *      - `computed.otherCategories` returns remaining
 *        categories alphabetised.
 *      - Template restructured: single `<ul role="tablist">`
 *        containing the All tab + interleaved
 *        `<li role="presentation" aria-hidden="true"
 *        class="mw-le-layouts-categories-group-header">`
 *        rows + per-group v-for'd tabs. Same shape mirrored
 *        in the AI-714 mobile bottom-sheet.
 *
 *   2. index.css
 *      - `.mw-le-layouts-categories-group-header` typography:
 *        --font-label / --weight-label / --letter-label /
 *        --ese-text-muted / uppercase. `pointer-events: none`
 *        so the header row isn't accidentally clickable. First
 *        group header (Featured) gets no top margin (avoids
 *        double-spacing the All tab → Featured gap).
 *
 * Accessibility: group headers carry `role="presentation"` +
 * `aria-hidden="true"` so screen readers announce the tablist
 * linearly without group-header noise. AT users hear all
 * tabs as one unified list; sighted users get the visual
 * grouping.
 */
class LayoutsModalC4893bAI716CategoryHierarchyContractTest extends TestCase
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
    // Group A — featuredCategoryNames data field (PM-editable)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function featured_category_names_is_data_field_not_template_literal(): void
    {
        // Per spec: "Featured set should be configurable in code
        // so PM can re-weight later (not hardcoded in template)."
        // The list MUST live in data() not in the template.
        $this->assertMatchesRegularExpression(
            '/featuredCategoryNames:\s*\[/',
            $this->vue,
            'featuredCategoryNames must be declared in data() as a configurable array (per spec "not hardcoded in template").'
        );
    }

    #[Test]
    public function featured_category_names_contains_six_designer_specified_entries(): void
    {
        foreach (['Content', 'Header', 'Features', 'Hero', 'Call To Action', 'Gallery'] as $name) {
            $this->assertMatchesRegularExpression(
                "/featuredCategoryNames:\\s*\\[[\\s\\S]{0,500}'" . preg_quote($name, '/') . "'/",
                $this->vue,
                "featuredCategoryNames must include `'{$name}'` (designer-specified 6 high-use categories)."
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — computed properties
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function featured_categories_computed_filters_to_actual_layouts(): void
    {
        // featuredCategories should filter to only entries present
        // in `layoutsList.categories` so a stale Featured entry
        // doesn't render an empty tab.
        $this->assertMatchesRegularExpression(
            '/featuredCategories\(\)\s*\{[\s\S]{0,500}this\.featuredCategoryNames\.filter\([\s\S]{0,200}cats\.includes\(name\)/',
            $this->vue,
            'featuredCategories() must filter featuredCategoryNames by inclusion in layoutsList.categories so stale entries are silently skipped.'
        );
    }

    #[Test]
    public function other_categories_computed_is_alphabetised(): void
    {
        // Spec: "the remaining 11" alphabetised.
        $this->assertMatchesRegularExpression(
            '/otherCategories\(\)\s*\{[\s\S]{0,500}localeCompare/',
            $this->vue,
            'otherCategories() must use localeCompare for alphabetical sort per spec "alphabetised".'
        );
    }

    #[Test]
    public function other_categories_excludes_featured(): void
    {
        // Other should be the SET-DIFFERENCE of all categories
        // minus featured — using a Set lookup is fine; a filter
        // call with .has() or .includes() on featuredCategoryNames
        // is the natural shape.
        $this->assertMatchesRegularExpression(
            '/otherCategories\(\)\s*\{[\s\S]{0,500}featuredSet\.has\(name\)/',
            $this->vue,
            'otherCategories() must exclude entries in featuredCategoryNames (set-difference) so categories never appear twice.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Desktop rail template renders 3 groups
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function desktop_rail_renders_group_header_for_featured(): void
    {
        // v-if="featuredCategories.length" so the header doesn't
        // render when no featured categories exist (e.g. PM
        // emptied the array).
        $this->assertMatchesRegularExpression(
            '/<li\s+v-if="featuredCategories\.length"[\s\S]{0,300}class="mw-le-layouts-categories-group-header"[\s\S]{0,300}\$lang\([\'"]Featured[\'"]/',
            $this->vue,
            'Desktop rail must render a `<li class="mw-le-layouts-categories-group-header">Featured</li>` header gated on featuredCategories.length.'
        );
    }

    #[Test]
    public function desktop_rail_renders_group_header_for_other(): void
    {
        $this->assertMatchesRegularExpression(
            '/<li\s+v-if="otherCategories\.length"[\s\S]{0,300}class="mw-le-layouts-categories-group-header"[\s\S]{0,300}\$lang\([\'"]All categories[\'"]/',
            $this->vue,
            'Desktop rail must render an `<li class="mw-le-layouts-categories-group-header">All categories</li>` header gated on otherCategories.length.'
        );
    }

    #[Test]
    public function group_headers_are_aria_hidden_and_role_presentation(): void
    {
        // Headers MUST NOT be exposed as tabs — they should be
        // invisible to assistive tech so the role="tablist"
        // semantics stay clean.
        $headerOccurrences = preg_match_all(
            '/<li[^>]*role="presentation"\s+aria-hidden="true"[^>]*class="mw-le-layouts-categories-group-header"/',
            $this->vue,
            $matches
        );
        $this->assertGreaterThanOrEqual(
            2,
            $headerOccurrences,
            'At least 2 occurrences of <li role="presentation" aria-hidden="true" class="mw-le-layouts-categories-group-header"> must appear (one per surface for Featured header). Found: ' . $headerOccurrences
        );
    }

    #[Test]
    public function desktop_rail_v_for_iterates_featured_and_other_separately(): void
    {
        // Two separate v-for loops — one over featuredCategories
        // (spec order preserved), one over otherCategories
        // (alphabetised). This is what gives the visual
        // hierarchy.
        $this->assertMatchesRegularExpression(
            '/v-for="categoryName in featuredCategories"/',
            $this->vue,
            'Desktop rail must contain `v-for="categoryName in featuredCategories"` so the Featured group renders in spec order.'
        );
        $this->assertMatchesRegularExpression(
            '/v-for="categoryName in otherCategories"/',
            $this->vue,
            'Desktop rail must contain `v-for="categoryName in otherCategories"` so the remainder renders alphabetised.'
        );
    }

    #[Test]
    public function legacy_single_v_for_over_layouts_list_categories_is_gone(): void
    {
        // The pre-AI-716 `v-for="categoryName in
        // layoutsList.categories"` flat-list shape MUST be
        // removed. If it lingers it'll double-render every
        // category alongside the new grouped lists.
        $this->assertDoesNotMatchRegularExpression(
            '/v-for="categoryName in layoutsList\.categories"/',
            $this->vue,
            'Pre-AI-716 flat-list `v-for="categoryName in layoutsList.categories"` must be REPLACED by the two grouped v-fors.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Mobile bottom-sheet mirrors the desktop 3-group shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function mobile_sheet_iterates_featured_with_mobile_helper(): void
    {
        // Mobile bottom-sheet must use filterCategoryFromMobile
        // (which selects + closes) and use the same
        // featuredCategories computed property — single source
        // of truth across viewports.
        $this->assertMatchesRegularExpression(
            '/mw-le-layouts-mobile-filter-sheet__list[\s\S]{0,3000}v-for="categoryName in featuredCategories"[\s\S]{0,1000}v-on:click="filterCategoryFromMobile/',
            $this->vue,
            'Mobile bottom-sheet must include `v-for="categoryName in featuredCategories"` calling filterCategoryFromMobile — mirrors desktop grouping.'
        );
    }

    #[Test]
    public function mobile_sheet_iterates_other_with_mobile_helper(): void
    {
        $this->assertMatchesRegularExpression(
            '/mw-le-layouts-mobile-filter-sheet__list[\s\S]{0,3000}v-for="categoryName in otherCategories"[\s\S]{0,1000}v-on:click="filterCategoryFromMobile/',
            $this->vue,
            'Mobile bottom-sheet must include `v-for="categoryName in otherCategories"` calling filterCategoryFromMobile.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — CSS group-header typography
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function group_header_uses_font_label_typography(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.modules-list-categories\s+\.mw-le-layouts-categories-group-header\s*\{[^}]*font-size:\s*var\(--font-label/s',
            $this->css,
            'Group header must use var(--font-label) per spec "group header in --font-label".'
        );
    }

    #[Test]
    public function group_header_is_uppercase_and_muted(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.modules-list-categories\s+\.mw-le-layouts-categories-group-header\s*\{[^}]*color:\s*var\(--ese-text-muted[\s\S]{0,300}text-transform:\s*uppercase/s',
            $this->css,
            'Group header must use --ese-text-muted text colour + text-transform: uppercase (metadata typography).'
        );
    }

    #[Test]
    public function group_header_is_not_clickable(): void
    {
        // pointer-events: none so the header row can't be tapped.
        $this->assertMatchesRegularExpression(
            '/\.modules-list-categories\s+\.mw-le-layouts-categories-group-header\s*\{[^}]*pointer-events:\s*none/s',
            $this->css,
            'Group header must have pointer-events: none — decorative row, not a tab.'
        );
    }

    #[Test]
    public function group_header_uses_space_sm_top_margin(): void
    {
        // Spec: "Three groups separated by var(--space-sm)".
        $this->assertMatchesRegularExpression(
            '/\.modules-list-categories\s+\.mw-le-layouts-categories-group-header\s*\{[^}]*margin-block-start:\s*var\(--space-sm,\s*8px\)/s',
            $this->css,
            'Group header must have margin-block-start: var(--space-sm) per spec "groups separated by --space-sm".'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group F — Markers + token-fallback hygiene
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString('task-2026-05-16-c4893b', $this->vue);
        $this->assertStringContainsString('task-2026-05-16-c4893b', $this->css);
    }

    #[Test]
    public function ai716_marker_present_in_both_files(): void
    {
        $this->assertStringContainsString('AI-716', $this->vue);
        $this->assertStringContainsString('AI-716', $this->css);
    }

    #[Test]
    public function css_tokens_carry_literal_fallbacks(): void
    {
        // Slice past the AI-716 docblock close so docblock
        // prose doesn't self-match (LESSONS guard, 8th
        // session-occurrence).
        $marker = strpos($this->css, 'AI-716 — Add Layouts left-rail category hierarchy');
        $this->assertNotFalse($marker, 'AI-716 task marker must be present in index.css.');
        $docblockEnd = strpos($this->css, '*/', $marker);
        $this->assertNotFalse($docblockEnd);
        $slice = substr($this->css, $docblockEnd + 2);

        $tokens = [
            '--space-xs'       => '6px',
            '--space-sm'       => '8px',
            '--space-md'       => '13px',
            '--font-label'     => '11px',
            '--weight-label'   => '500',
            '--letter-label'   => '0.01em',
            '--ese-text-muted' => '#6b7280',
        ];
        foreach ($tokens as $token => $fallback) {
            $this->assertMatchesRegularExpression(
                '/var\(' . preg_quote($token, '/') . ',\s*[^)]*' . preg_quote($fallback, '/') . '/',
                $slice,
                "Token {$token} must be consumed as var({$token}, <literal {$fallback}>) in the AI-716 slice."
            );
        }
    }
}
