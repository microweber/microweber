<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-147 / AI-170 — dedicated Live Edit sidebar hamburger toggle.
 *
 * Tester re-test of cycle-143/146 confirmed the
 * `#mw-live-edit-toolbar-back-to-admin-link` IS visible AND DOES
 * trigger `mw.app.liveEditWidgets.toggleAdminSidebar()`. But:
 *   - The cycle-143 44x44 rule lost the cascade fight (tester
 *     measured 32x32) — the min-width/min-height declarations had
 *     no `!important`.
 *   - The visible glyph is an arrow-circle (the "back to admin"
 *     icon), not a hamburger. Users tap looking for a sidebar
 *     toggle and don't recognise the affordance.
 *   - The visible "ADMIN" text label is misleading on mobile —
 *     the link doesn't navigate to admin, it opens the sidebar.
 *
 * Cycle-147 fix: CSS-only swap on mobile that
 *   1. Reinforces 44x44 with `!important` on min-width/min-height
 *      so the cascade fight is won.
 *   2. Hides the arrow-circle SVG.
 *   3. Hides the misleading "ADMIN" text.
 *   4. Injects a hamburger ☰ glyph via `::before` pseudo (three
 *      horizontal bars rendered via box-shadow on a single line).
 *
 * The aria-label stays "Back to admin" — CSS can't rewrite ARIA
 * attributes. That semantic mismatch is documented as phase-2
 * follow-up; a future Vue-source edit can update the Vue prop.
 */
class Ai170LiveEditSidebarHamburgerContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function back_to_admin_link_44x44_uses_important(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // Tester reported 32x32 on the link — meaning the cycle-143
        // min-width/min-height declarations lost the cascade fight.
        // Cycle-147 MUST use !important on both min-width and
        // min-height so the floor wins.
        $this->assertMatchesRegularExpression(
            '/#mw-live-edit-toolbar-back-to-admin-link[\s\S]{0,500}min-width:\s*44px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST use min-width:44px !important on '
            . '#mw-live-edit-toolbar-back-to-admin-link so the cycle-147 '
            . 'rule wins against the higher-specificity 32x32 declaration '
            . 'tester measured.'
        );
        $this->assertMatchesRegularExpression(
            '/#mw-live-edit-toolbar-back-to-admin-link[\s\S]{0,500}min-height:\s*44px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST use min-height:44px !important too.'
        );
    }

    #[Test]
    public function arrow_icon_is_hidden_on_mobile(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        $this->assertMatchesRegularExpression(
            '/#mw-live-edit-toolbar-back-to-admin-link\s+\.mw-live-edit-toolbar-arrow-icon[\s\S]{0,150}display:\s*none\s*!important/m',
            $src,
            'live-edit-mobile.css MUST hide the existing arrow-circle '
            . 'SVG (.mw-live-edit-toolbar-arrow-icon) on mobile so the '
            . 'hamburger glyph becomes the dominant affordance.'
        );
    }

    #[Test]
    public function admin_text_label_is_hidden_on_mobile(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        $this->assertMatchesRegularExpression(
            '/#mw-live-edit-toolbar-back-to-admin-link\s*>\s*span[\s\S]{0,150}display:\s*none\s*!important/m',
            $src,
            'live-edit-mobile.css MUST hide the visible "ADMIN" text '
            . 'span on mobile — it was misleading (the link toggles '
            . 'the sidebar drawer, not navigates to admin).'
        );
    }

    #[Test]
    public function hamburger_glyph_is_injected_via_before_pseudo(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        $this->assertMatchesRegularExpression(
            '/#mw-live-edit-toolbar-back-to-admin-link::before[\s\S]{0,500}box-shadow:[^;]*currentColor[^;]*currentColor/m',
            $src,
            'live-edit-mobile.css MUST inject a hamburger glyph (3 '
            . 'horizontal bars rendered via box-shadow on a ::before '
            . 'pseudo-element) on the back-to-admin link on mobile.'
        );

        $this->assertMatchesRegularExpression(
            '/#mw-live-edit-toolbar-back-to-admin-link::before[\s\S]{0,400}background:\s*currentColor/m',
            $src,
            'The ::before pseudo MUST use currentColor for the bar '
            . 'fill so the hamburger inherits text colour from the '
            . 'parent (works in light + dark themes without tweaking).'
        );
    }

    #[Test]
    public function ai_170_anchor_documents_the_swap_on_mobile(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        $this->assertStringContainsString(
            'AI-170',
            $src,
            'live-edit-mobile.css MUST carry the AI-170 anchor inline '
            . 'so the cycle-147 swap is discoverable at refactor time.'
        );
        $this->assertStringContainsString(
            'cycle-147',
            $src,
            'live-edit-mobile.css MUST carry the cycle-147 anchor inline.'
        );
    }

    #[Test]
    public function built_bundle_carries_the_swap_rules(): void
    {
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped(
                "Built bundle not present at {$rel}; skipping production-CSS pin."
            );
        }
        $built = file_get_contents($path);

        // Functional pins: every load-bearing piece of the AI-170 fix
        // MUST appear in the BUILT CSS bundle (not just the source).
        // Skipping any of these means the bundle wasn't rebuilt after
        // the source edit — same regression class as cycle-142.
        $this->assertStringContainsString(
            '#mw-live-edit-toolbar-back-to-admin-link::before',
            $built,
            'Built bundle MUST contain the ::before pseudo rule (the '
            . 'hamburger glyph injection). If missing, the bundle '
            . 'was not rebuilt after the source edit.'
        );

        $this->assertMatchesRegularExpression(
            '/min-width:\s*44px\s*!important/m',
            $built,
            'Built bundle MUST contain min-width:44px !important so '
            . 'the back-to-admin link wins the 32x32 cascade fight.'
        );
    }
}
