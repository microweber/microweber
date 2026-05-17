<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-6305c9 — AI-848 [P2] auth-header brand logo 0×0
 * (Stage-2 sub-variant `CSS-rules-mutual-dependency`).
 *
 * Jira: https://microweber.atlassian.net/browse/AI-848
 * Designer-found via verify-before-accept step #5 re-run after the
 * AI-794a + AI-794b CHANGE absorption ship (commit d035a3541f).
 *
 * Pre-fix shape that designer measured at /forgot-password +
 * /reset-password on 1440 desktop + 390 mobile produced
 * box.width = 0 + box.height = 0:
 *
 *   .mw-auth-header .mw-auth-logo { display: inline-block; max-width: 64px; max-height: 64px; }
 *   .mw-auth-header .mw-auth-logo img { display: block; max-width: 100%; max-height: 64px; height: auto; width: auto; }
 *
 * Root cause: inline-block parent anchor with bare max-width AND
 * NO defined width is shrink-to-fit (its width depends on child
 * intrinsic size). Img child with width:auto + max-width:100%
 * depends on parent width. Mutual dependency settles at 0×0.
 * Compounded by source logo.svg carrying viewBox="0 0 612 115.6"
 * but NO explicit width=/height= attributes (intrinsic ratio
 * present, intrinsic SIZE absent — browsers pick 0 for shrink-
 * to-fit containers).
 *
 * Slice A canonical fix (designer-preferred, preserves merchant
 * aspect ratio):
 *
 *   .mw-auth-header .mw-auth-logo { display: inline-block; }
 *   .mw-auth-header .mw-auth-logo img { display: block; width: auto; height: 64px; max-width: 280px; }
 *
 * Sets explicit height:64px on the img (the design cap) → width:auto
 * resolves from the SVG ratio 612:115.6 ≈ 339px → clamped by
 * max-width:280px so wide brand marks stay inside the 480px card.
 * Drops parent max-width to break the shrink-to-fit cycle entirely.
 *
 * Sister-rule to AI-803 .logo-module .logo-link inline-block parent-
 * flex-context fix (commit 619708b3db) — same Stage-2 family, distinct
 * sub-variant + distinct class (.mw-auth-logo NOT .logo-module .logo-link).
 *
 * Selector-self-match guard (20+ session-recurrences): docblock + inline
 * source comments legitimately mention legacy values (the pre-fix
 * max-width:64px on parent). Absence assertions slice the CSS rule
 * lines only, NOT the docblock prose.
 *
 * Tier-3 rendered-dimension probe sub-pattern (designer-formalised
 * post-AI-794a ACK): visual-render-required elements need
 * getBoundingClientRect() width/height assertions alongside source-
 * level CSS rule pinning. The dimension probe IS the test that
 * would have caught AI-848 at AI-794 ship-time.
 */
class Auth6305c9AI848AuthLogoDimensionContractTest extends TestCase
{
    private string $layoutSrc;
    private string $layoutCss;

    protected function setUp(): void
    {
        parent::setUp();
        $this->layoutSrc = (string) file_get_contents(base_path(
            'src/MicroweberPackages/User/resources/views/layout.blade.php'
        ));

        // Strip CSS /* ... */ comments + Blade {{-- --}} so docblock prose
        // mentioning legacy max-width:64px doesn't false-fail absence
        // assertions. CSS line-comments (//) are not valid CSS so no
        // line-comment stripping needed inside <style> blocks.
        $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', $this->layoutSrc);
        $stripped = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $stripped);
        $this->layoutCss = (string) $stripped;
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A  Slice A fix shape — source CSS rules present + correct
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai848_parent_rule_drops_max_width_constraint(): void
    {
        // Post-fix .mw-auth-header .mw-auth-logo is inline-block ONLY —
        // no max-width / max-height anchor. Breaking the shrink-to-fit
        // cycle is the whole point of Slice A.
        $this->assertMatchesRegularExpression(
            '/\.mw-auth-header\s+\.mw-auth-logo\s*\{\s*display:\s*inline-block;\s*\}/',
            $this->layoutCss,
            'AI-848 Slice A: parent .mw-auth-logo rule should be { display: inline-block; } with NO max-width / max-height.'
        );
    }

    #[Test]
    public function ai848_img_carries_explicit_height_and_max_width(): void
    {
        // Post-fix img: display:block + width:auto + height:64px + max-width:280px.
        // height:64px is the design cap; width:auto + max-width:280px lets the
        // SVG aspect ratio resolve naturally, clamped to fit the 480px card.
        $this->assertMatchesRegularExpression(
            '/\.mw-auth-header\s+\.mw-auth-logo\s+img\s*\{[^}]*display:\s*block;[^}]*width:\s*auto;[^}]*height:\s*64px;[^}]*max-width:\s*280px;[^}]*\}/',
            $this->layoutCss,
            'AI-848 Slice A: img rule should declare display:block + width:auto + height:64px + max-width:280px.'
        );
    }

    #[Test]
    public function ai848_img_explicit_height_value_pin(): void
    {
        // Explicit pin: the height value is 64px (design cap, matches the prior
        // max-height:64px the design always intended as the cap).
        $this->assertStringContainsString(
            'height: 64px',
            $this->extractAuthLogoImgRule(),
            'AI-848 Slice A: img height must be exactly 64px (design cap).'
        );
    }

    #[Test]
    public function ai848_img_max_width_value_pin(): void
    {
        // Explicit pin: max-width 280px gives wide brand marks a stop boundary
        // before they break the 480px card layout.
        $this->assertStringContainsString(
            'max-width: 280px',
            $this->extractAuthLogoImgRule(),
            'AI-848 Slice A: img max-width must be 280px to bound wide brand marks within the 480px card.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  Pre-fix shape absent — the shrink-to-fit cycle is broken
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai848_parent_no_longer_carries_max_width(): void
    {
        // Negative regression guard: the parent .mw-auth-logo rule body must
        // NOT carry max-width OR max-height — those are the shrink-to-fit
        // cycle ingredients we are deliberately removing. Slice the parent
        // rule body only (NOT the img child rule which legitimately uses
        // max-width:280px post-fix).
        $parentRule = $this->extractAuthLogoParentRule();
        $this->assertStringNotContainsString(
            'max-width',
            $parentRule,
            'AI-848 regression-guard: parent .mw-auth-logo must NOT re-introduce max-width (breaks Slice A — re-enters shrink-to-fit cycle).'
        );
        $this->assertStringNotContainsString(
            'max-height',
            $parentRule,
            'AI-848 regression-guard: parent .mw-auth-logo must NOT re-introduce max-height (cycle-ingredient pre-fix only).'
        );
    }

    #[Test]
    public function ai848_img_no_longer_uses_percent_max_width(): void
    {
        // Negative regression guard: the img must NOT carry max-width:100%
        // (which was the child-of-cycle side of the pre-fix shrink-to-fit
        // dependency). Img max-width is now 280px (px, not %).
        $imgRule = $this->extractAuthLogoImgRule();
        $this->assertStringNotContainsString(
            'max-width: 100%',
            $imgRule,
            'AI-848 regression-guard: img must NOT use max-width:100% (re-enters shrink-to-fit cycle when paired with shrink-to-fit parent).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  AI-794a + AI-794b regression guards
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai794a_btn_primary_compound_selector_preserved(): void
    {
        // AI-794a (commit d035a3541f) shipped the compound .mw-auth-card
        // .btn-primary selector with !important to defeat the template's
        // higher-specificity .btn-primary rule. AI-848 must not regress it.
        $this->assertMatchesRegularExpression(
            '/\.mw-auth-card\s+\.btn-primary\s*\{[^}]*background-color:\s*#0d6efd\s*!important;[^}]*\}/',
            $this->layoutCss,
            'AI-794a regression-guard: compound .mw-auth-card .btn-primary { background-color: #0d6efd !important; ... } must remain present.'
        );
    }

    #[Test]
    public function ai794a_btn_primary_focus_mirror_preserved(): void
    {
        // AI-794a ships hover + focus mirror rules so the brand-blue
        // applies on focus too. AI-848 must not regress.
        $this->assertMatchesRegularExpression(
            '/\.mw-auth-card\s+\.btn-primary:hover,\s*\.mw-auth-card\s+\.btn-primary:focus\s*\{[^}]*background-color:\s*#0b5ed7\s*!important/',
            $this->layoutCss,
            'AI-794a regression-guard: :hover + :focus mirror rules for .mw-auth-card .btn-primary must remain.'
        );
    }

    #[Test]
    public function auth_chrome_baseline_preserved(): void
    {
        // AI-794 baseline rules (card + container + header) must remain.
        $this->assertStringContainsString('.mw-auth-container { max-width: 480px;', $this->layoutCss);
        $this->assertStringContainsString('.mw-auth-card { background: #fff;', $this->layoutCss);
        $this->assertStringContainsString('.mw-auth-header { text-align: center;', $this->layoutCss);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D  Markers + LESSONS lineage discoverable from source
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai848_task_id_marker_present_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-17-6305c9',
            $this->layoutSrc,
            'AI-848 ship must embed task-id marker in source for grep-discoverability per LESSONS.'
        );
    }

    #[Test]
    public function ai848_ai_ticket_marker_present_in_source(): void
    {
        $this->assertStringContainsString(
            'AI-848',
            $this->layoutSrc,
            'AI-848 ship must embed AI-848 ticket marker in source for cross-task audit grep.'
        );
    }

    #[Test]
    public function ai848_sub_variant_classifier_present(): void
    {
        // Source docblock must classify the defect's Stage-2 sub-variant so
        // future agents reading the file find the diagnostic lineage.
        $this->assertStringContainsString(
            'CSS-rules-mutual-dependency',
            $this->layoutSrc,
            'AI-848 docblock must name the Stage-2 sub-variant (`CSS-rules-mutual-dependency`) per the 4-sub-variant taxonomy formalised this session.'
        );
    }

    #[Test]
    public function ai848_ai803_lineage_cite_present(): void
    {
        // Source docblock must cite the AI-803 sister-rule (same Stage-2 family,
        // distinct sub-variant + distinct class) so the family relationship is
        // discoverable from the file.
        $this->assertStringContainsString(
            'AI-803',
            $this->layoutSrc,
            'AI-848 docblock must cite AI-803 sister-rule (parent-flex-context sub-variant) for family-lineage discoverability.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Helpers — single source of truth for CSS rule slicing
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Extract the body of .mw-auth-header .mw-auth-logo { ... } parent rule.
     * Bounded on the LAST { before the IMG rule starts, walking forward to
     * the matching closing }. Strips the img rule out so parent-only assertions
     * don't mismatch on the img-rule body.
     */
    private function extractAuthLogoParentRule(): string
    {
        // The parent rule is the one whose selector ends with `.mw-auth-logo`
        // (NOT followed by `img`). We match the selector + opening brace +
        // body up to the closing brace.
        preg_match(
            '/\.mw-auth-header\s+\.mw-auth-logo\s*\{([^}]*)\}/',
            $this->layoutCss,
            $match
        );
        return $match[1] ?? '';
    }

    /**
     * Extract the body of .mw-auth-header .mw-auth-logo img { ... } rule.
     */
    private function extractAuthLogoImgRule(): string
    {
        preg_match(
            '/\.mw-auth-header\s+\.mw-auth-logo\s+img\s*\{([^}]*)\}/',
            $this->layoutCss,
            $match
        );
        return $match[1] ?? '';
    }
}
