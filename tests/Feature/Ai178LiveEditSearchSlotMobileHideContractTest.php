<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-152 / AI-178 — Live Edit toolbar "Search content" input
 * collapses to 2px wide on mobile (P1).
 *
 * UX-audit P1 finding (agent-test mobile-ux-audit-report.md):
 * "Invisible/untappable search field in Live Edit toolbar at 390×844".
 *
 * Reproduction at 390×844:
 *   - `#mw-live-edit-search-content` (the slot mw.autoComplete renders
 *     into) sits inside a Bootstrap `col col-xl-auto
 *     toolbar-col-container` parent.
 *   - At >=xl the parent is `auto` (content-sized); below xl the col
 *     falls back to default flex behaviour. Between the cycle-126/143
 *     toolbar buttons (☰ + ← + → + +ADD on the left, device-toggle +
 *     tools menu + VIEW + SAVE on the right) only ~48px of horizontal
 *     space remains for the search slot.
 *   - With the autoComplete dropdown's `ps-4` padding-left for the
 *     search-icon prefix, the actual <input> ends up ~2px wide —
 *     measurable but completely unusable.
 *
 * Cycle-152 fix (CSS-only):
 *   - Hide `#mw-live-edit-search-content` AND `.mw-live-edit-search-
 *     dropdown` on viewports <=768px so the broken control disappears.
 *   - Also `display: none` the parent `.toolbar-col-container` so the
 *     freed ~48px of horizontal space goes back to the rest of the
 *     toolbar — uses `:has()` to scope the collapse to the col that
 *     contains the search slot only.
 *
 * Power-user content-search remains available on tablet+ (>=769px)
 * and on desktop. Mobile users navigate via the admin sidebar (Pages
 * list / Posts list) which is reachable through the AI-170 sidebar
 * hamburger.
 */
class Ai178LiveEditSearchSlotMobileHideContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_ai_178_anchor(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');
        $this->assertStringContainsString('AI-178', $src,
            'live-edit-mobile.css MUST carry the AI-178 anchor inline.');
        $this->assertStringContainsString('cycle-152', $src,
            'live-edit-mobile.css MUST carry the cycle-152 anchor inline.');
    }

    #[Test]
    public function source_hides_search_slot_on_mobile(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // The `#mw-live-edit-search-content` slot must be hidden.
        $this->assertMatchesRegularExpression(
            '/#mw-live-edit-search-content[\s\S]{0,500}display:\s*none\s*!important/m',
            $src,
            'live-edit-mobile.css MUST hide #mw-live-edit-search-content '
            . 'on mobile so the broken 2px-wide search input no longer '
            . 'renders.'
        );
        // The .mw-live-edit-search-dropdown wrapper must also be hidden.
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-search-dropdown[\s\S]{0,500}display:\s*none\s*!important/m',
            $src,
            'live-edit-mobile.css MUST hide .mw-live-edit-search-dropdown '
            . 'too — the autoComplete may render the dropdown wrapper '
            . 'around the slot or as a sibling.'
        );
    }

    #[Test]
    public function source_collapses_parent_col_to_free_toolbar_space(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // The parent .toolbar-col-container must collapse so the freed
        // ~48px of horizontal space is reclaimed by the rest of the
        // toolbar. Uses :has() so the collapse only applies to the col
        // that holds the search slot, not every toolbar-col-container.
        $this->assertMatchesRegularExpression(
            '/\.toolbar-col-container:has\(>\s*#mw-live-edit-search-content\)[\s\S]{0,500}display:\s*none\s*!important/m',
            $src,
            'live-edit-mobile.css MUST collapse the .toolbar-col-container '
            . 'parent that wraps #mw-live-edit-search-content via :has() '
            . 'so the freed ~48px goes back to the toolbar buttons.'
        );
    }

    #[Test]
    public function rule_is_inside_max_width_768_block(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        $anchorPos = strpos($src, 'AI-178');
        $this->assertNotFalse($anchorPos, 'AI-178 anchor must be present.');

        // First selector after the anchor should be inside @media (max-width: 768px)
        $rulePos = strpos($src, '#mw-live-edit-search-content', $anchorPos);
        $this->assertNotFalse($rulePos, 'AI-178 rule must follow the anchor.');

        $beforeRule = substr($src, 0, $rulePos);
        $lastMediaPos = strrpos($beforeRule, '@media');
        $this->assertNotFalse($lastMediaPos, 'AI-178 rule must sit inside an @media block.');

        $mediaQueryLine = substr($src, $lastMediaPos, 60);
        $this->assertStringContainsString('max-width: 768px', $mediaQueryLine,
            'AI-178 rule MUST be inside `@media (max-width: 768px)` so '
            . 'the search slot is preserved on tablet+ / desktop where '
            . 'there is enough horizontal space for it to be usable.');
    }

    #[Test]
    public function admin_live_edit_page_scope_used(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // Both .mw-admin-live-edit-page and .mw-live-edit-page scopes
        // must appear so the rule binds whichever class the live-edit
        // ancestry carries (per cycle-149 lesson — see AI-177).
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-live-edit-page\s+#mw-live-edit-search-content/m',
            $src,
            'live-edit-mobile.css MUST scope the search-slot hide rule '
            . 'under .mw-admin-live-edit-page (matches the existing '
            . 'admin live-edit toolbar scope).'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+#mw-live-edit-search-content/m',
            $src,
            'live-edit-mobile.css MUST also scope under .mw-live-edit-page '
            . '(defensive duplicate for any future contexts that carry '
            . 'only that class).'
        );
    }

    #[Test]
    public function built_bundle_carries_search_hide_rules(): void
    {
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built filament-theme bundle missing; skipping production-CSS pin.");
        }
        $built = file_get_contents($path);

        // Functional pin per cycle-142 lesson: load-bearing pieces MUST
        // appear in the built bundle.
        $this->assertStringContainsString('#mw-live-edit-search-content', $built,
            'Built bundle MUST contain the search-slot hide rule. If '
            . 'missing, the bundle was not rebuilt after the source edit.');
        $this->assertMatchesRegularExpression(
            '/#mw-live-edit-search-content[\s\S]{0,500}display:\s*none\s*!important/m',
            $built,
            'Built bundle MUST contain display:none !important on '
            . '#mw-live-edit-search-content.'
        );
        // Parent collapse rule via :has()
        $this->assertStringContainsString('toolbar-col-container:has(', $built,
            'Built bundle MUST contain the :has() parent-col collapse '
            . 'rule so the freed toolbar space is reclaimed.');
    }
}
