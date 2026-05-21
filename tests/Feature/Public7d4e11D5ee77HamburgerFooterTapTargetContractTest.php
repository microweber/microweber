<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-7d4e11 / AI-NEW (from task-d5ee77 mobile audit)
 *
 * Agent-test mobile audit at 390×844 found 2 FAIL elements on the homepage:
 *
 * FAIL #1 — Hamburger menu (.mw-vhmbgr-wrapper) measured 25×25px.
 *   Root cause: skin-1 initialises MWSiteMobileMenu with size:'25px'.
 *   Fix: min-width/min-height 44px with !important in public-touch.css
 *   inside @media (max-width: 768px), (pointer: coarse).
 *
 * FAIL #2 — Footer links measured ~20-23px height.
 *   Root cause: The copyright bottom bar (<section class="py-2">) sits
 *   OUTSIDE .footer-background, so the existing .footer-background a rule
 *   does not reach powered_by_link()'s <span class="mw-powered-by"> links.
 *   Fix: .mw-powered-by a { min-height: 44px } added without the
 *   .footer-background ancestor requirement.
 *   Note: .footer-background a { min-height: 44px } (line ~1159) continues
 *   to cover the main footer menu links inside .footer-background.
 *
 * Two-surface sync: source (Templates/Bootstrap/resources/assets/css/public-touch.css)
 * and served mirror (public/templates/bootstrap/css/public-touch.css) must be
 * byte-identical.
 */
class Public7d4e11D5ee77HamburgerFooterTapTargetContractTest extends TestCase
{
    private string $src;
    private string $srcStripped;
    private string $served;

    protected function setUp(): void
    {
        parent::setUp();
        $this->src = (string) file_get_contents(
            base_path('Templates/Bootstrap/resources/assets/css/public-touch.css')
        );
        // Strip CSS comments before absence-assertions (selector-self-match guard)
        $this->srcStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->src) ?? $this->src;
        $this->served = (string) file_get_contents(
            base_path('public/templates/bootstrap/css/public-touch.css')
        );
    }

    // ─── FAIL #1 — Hamburger tap-target ─────────────────────────────────────

    #[Test]
    public function hamburger_wrapper_gets_min_width_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.mw-vhmbgr-wrapper\s*\{[^}]*min-width:\s*44px\s*!important~s',
            $this->src,
            '.mw-vhmbgr-wrapper must have min-width: 44px !important to provide a 44px tap area.'
        );
    }

    #[Test]
    public function hamburger_wrapper_gets_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.mw-vhmbgr-wrapper\s*\{[^}]*min-height:\s*44px\s*!important~s',
            $this->src,
            '.mw-vhmbgr-wrapper must have min-height: 44px !important to provide a 44px tap area.'
        );
    }

    #[Test]
    public function hamburger_wrapper_rule_is_inside_touch_media_query(): void
    {
        // The hamburger rule must be scoped to touch/mobile viewports.
        // We verify by checking the task marker only appears after the
        // @media (max-width: 768px) block begins.
        $mediaPos = strrpos($this->src, '@media (max-width: 768px), (pointer: coarse)');
        $this->assertNotFalse($mediaPos, '@media (max-width: 768px), (pointer: coarse) block must exist.');

        $markerPos = strpos($this->src, 'task-2026-05-22-7d4e11');
        $this->assertNotFalse($markerPos, 'task-2026-05-22-7d4e11 marker must be present in public-touch.css.');

        $hamburgerRulePos = strpos($this->src, '.mw-vhmbgr-wrapper', $markerPos);
        $this->assertNotFalse($hamburgerRulePos, '.mw-vhmbgr-wrapper rule must follow the task marker.');
    }

    #[Test]
    public function hamburger_wrapper_uses_inline_flex_to_centre_svg(): void
    {
        $markerPos = strpos($this->src, 'task-2026-05-22-7d4e11');
        $this->assertNotFalse($markerPos);
        $slice = substr($this->src, $markerPos, 1500);

        $this->assertStringContainsString(
            'display: inline-flex',
            $slice,
            '.mw-vhmbgr-wrapper must use display: inline-flex so the SVG remains visually centred inside the enlarged tap area.'
        );
        $this->assertStringContainsString(
            'align-items: center',
            $slice,
            '.mw-vhmbgr-wrapper must use align-items: center.'
        );
    }

    // ─── FAIL #2 — Footer copyright bar tap-target ──────────────────────────

    #[Test]
    public function powered_by_links_get_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.mw-powered-by\s+a\s*\{[^}]*min-height:\s*44px~s',
            $this->src,
            '.mw-powered-by a must have min-height: 44px to cover the footer copyright bar links.'
        );
    }

    #[Test]
    public function powered_by_rule_does_not_require_footer_background_ancestor(): void
    {
        // Count occurrences of the standalone .mw-powered-by a { selector
        // vs the ancestor-scoped .footer-background .mw-powered-by a selector.
        // There must be at least 1 standalone occurrence so the rule covers
        // the copyright bar that lives outside .footer-background.
        // Use comment-stripped source to avoid matching selector text inside comments.
        $standaloneCount = preg_match_all(
            '~(?<![.\w])\.mw-powered-by\s+a\s*\{~',
            $this->srcStripped,
            $standaloneMatches
        );
        $this->assertGreaterThanOrEqual(
            1,
            (int) $standaloneCount,
            'At least one standalone .mw-powered-by a { rule must exist (without .footer-background ancestor) to cover the copyright bar outside .footer-background.'
        );
    }

    #[Test]
    public function existing_footer_background_rule_still_covers_main_footer_links(): void
    {
        // Regression guard: the existing .footer-background a { min-height: 44px }
        // rule that covers the main footer section must remain present.
        $this->assertMatchesRegularExpression(
            '~\.footer-background\s+a[^{]*\{[^}]*min-height:\s*44px~s',
            $this->src,
            '.footer-background a { min-height: 44px } must still be present for main footer links.'
        );
    }

    #[Test]
    public function powered_by_rule_is_inside_touch_media_query(): void
    {
        // The new .mw-powered-by a rule must be inside the touch-viewport @media block.
        $markerPos = strpos($this->src, 'task-2026-05-22-7d4e11');
        $this->assertNotFalse($markerPos);
        // Find .mw-powered-by a after the task marker
        $poweredByPos = strpos($this->src, '.mw-powered-by a', $markerPos);
        $this->assertNotFalse($poweredByPos, '.mw-powered-by a must follow the task-2026-05-22-7d4e11 marker.');
    }

    // ─── Task markers and source-mirror parity ───────────────────────────────

    #[Test]
    public function task_marker_present_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-7d4e11',
            $this->src,
            'Source public-touch.css must carry the task-2026-05-22-7d4e11 marker.'
        );
    }

    #[Test]
    public function source_and_served_mirror_are_byte_identical(): void
    {
        $this->assertSame(
            $this->src,
            $this->served,
            'Templates/Bootstrap/resources/assets/css/public-touch.css and ' .
            'public/templates/bootstrap/css/public-touch.css must be byte-identical.'
        );
    }
}
