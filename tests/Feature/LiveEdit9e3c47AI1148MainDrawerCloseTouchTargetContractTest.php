<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-28-9e3c47 / AI-1148 — MainDrawer close button touch target.
 * Jira: https://microweber.atlassian.net/browse/AI-1148
 *
 * Pre-fix: the `.mw-main-drawer__close` rule in general-styles.css declared
 * `width: 32px; height: 32px;` — below the WCAG 2.5.5 Target Size (Enhanced)
 * 44x44 CSS-pixel floor that this codebase applies to interactive controls
 * across admin + checkout + Live Edit (see AI-517 / AI-518 / AI-519 lineage
 * + the project's mobile-touch.css convention).
 *
 * Fix: widen to `width: 44px; height: 44px;` AND add `min-width: 44px;
 * min-height: 44px;` as belt-and-braces against future descendant rules
 * that might shrink the button via width/height alone (defence-in-depth
 * mirrors the AI-517 / AI-518 :has() patterns). SVG glyph inside the
 * button stays 20x20px so the inner stroke geometry is unchanged; only
 * the click/tap target grows.
 *
 * Source-of-truth: packages/microweber-filament-theme/resources/assets/
 * css/microweber/general-styles.css (Webpack pipeline).
 * Served bundle: public/vendor/microweber-packages/microweber-filament-
 * theme/build/microweber-filament-theme.css.
 *
 * Selector-self-match guard: setUp() pre-strips CSS block comments before
 * any absence assertion in case docblock prose ever mentions a literal
 * pre-state signature (uniformity-rule from task-7aa48a).
 */
class LiveEdit9e3c47AI1148MainDrawerCloseTouchTargetContractTest extends TestCase
{
    private const SOURCE_CSS = 'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css';

    private const SERVED_CSS = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    private const DRAWER_VUE = 'packages/frontend-assets/resources/assets/ui/components/Toolbar/MainDrawer.vue';

    private string $sourceCss;

    private string $sourceCssStripped;

    private string $servedCss;

    private string $drawerVue;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sourceCss = (string) file_get_contents(base_path(self::SOURCE_CSS));
        $this->sourceCssStripped = $this->stripCssComments($this->sourceCss);
        $this->servedCss = (string) file_get_contents(base_path(self::SERVED_CSS));
        $this->drawerVue = (string) file_get_contents(base_path(self::DRAWER_VUE));
    }

    private function stripCssComments(string $source): string
    {
        return (string) preg_replace('~/\*[\s\S]*?\*/~', '', $source);
    }

    // ---------------------------------------------------------------------
    // Group A -- Source CSS carries the 44px touch target (post-fix)
    // ---------------------------------------------------------------------

    #[Test]
    public function source_close_button_rule_declares_width_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-main-drawer__close\s*\{[^}]*width:\s*44px\s*;/',
            $this->sourceCssStripped,
            'AI-1148: .mw-main-drawer__close MUST declare width: 44px to meet the WCAG 2.5.5 Target Size 44x44 CSS-pixel floor.'
        );
    }

    #[Test]
    public function source_close_button_rule_declares_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-main-drawer__close\s*\{[^}]*height:\s*44px\s*;/',
            $this->sourceCssStripped,
            'AI-1148: .mw-main-drawer__close MUST declare height: 44px to meet the WCAG 2.5.5 Target Size 44x44 CSS-pixel floor.'
        );
    }

    #[Test]
    public function source_close_button_rule_declares_min_width_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-main-drawer__close\s*\{[^}]*min-width:\s*44px\s*;/',
            $this->sourceCssStripped,
            'AI-1148: .mw-main-drawer__close MUST declare min-width: 44px as belt-and-braces defence-in-depth against future descendant rules.'
        );
    }

    #[Test]
    public function source_close_button_rule_declares_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-main-drawer__close\s*\{[^}]*min-height:\s*44px\s*;/',
            $this->sourceCssStripped,
            'AI-1148: .mw-main-drawer__close MUST declare min-height: 44px as belt-and-braces defence-in-depth against future descendant rules.'
        );
    }

    // ---------------------------------------------------------------------
    // Group B -- Legacy 32px pre-state absent (comment-stripped negative)
    // ---------------------------------------------------------------------

    #[Test]
    public function source_close_button_rule_has_no_32px_residue(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/\.mw-main-drawer__close\s*\{[^}]*width:\s*32px\s*;/',
            $this->sourceCssStripped,
            "AI-1148: legacy width: 32px MUST be absent from .mw-main-drawer__close — replaced by 44px for WCAG 2.5.5 compliance."
        );
        $this->assertDoesNotMatchRegularExpression(
            '/\.mw-main-drawer__close\s*\{[^}]*height:\s*32px\s*;/',
            $this->sourceCssStripped,
            "AI-1148: legacy height: 32px MUST be absent from .mw-main-drawer__close — replaced by 44px for WCAG 2.5.5 compliance."
        );
    }

    // ---------------------------------------------------------------------
    // Group C -- Served bundle (Webpack-built CSS) carries the fix
    // ---------------------------------------------------------------------

    #[Test]
    public function served_bundle_carries_44px_close_button(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-main-drawer__close\s*\{[^}]*width:\s*44px[\s\S]*?height:\s*44px[\s\S]*?min-width:\s*44px[\s\S]*?min-height:\s*44px/',
            $this->servedCss,
            'AI-1148: served microweber-filament-theme.css MUST carry the 44px close-button geometry — run `cd packages/microweber-filament-theme && npm run build` if absent.'
        );
    }

    // ---------------------------------------------------------------------
    // Group D -- SVG glyph stays 20x20 (regression guard: inner geometry
    //            unchanged; only the click/tap target grows)
    // ---------------------------------------------------------------------

    #[Test]
    public function close_button_svg_glyph_remains_20x20(): void
    {
        $this->assertMatchesRegularExpression(
            '/class="mw-main-drawer__close"[\s\S]*?<svg[^>]*width="20"[^>]*height="20"/',
            $this->drawerVue,
            'AI-1148 regression guard: the inner SVG glyph MUST stay 20x20 — only the surrounding button geometry grows. Pre-fix glyph rendered at 20x20 inside a 32x32 button; post-fix renders at 20x20 inside a 44x44 button.'
        );
    }

    // ---------------------------------------------------------------------
    // Group E -- task-id + AI-1148 markers (audit grep contract)
    // ---------------------------------------------------------------------

    #[Test]
    public function task_id_marker_present_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-9e3c47',
            $this->sourceCss,
            'AI-1148: general-styles.css MUST carry the AI-1148 task-id marker for cross-surface audit grep.'
        );
        $this->assertStringContainsString(
            'AI-1148',
            $this->sourceCss,
            'AI-1148: general-styles.css MUST cite the AI-1148 ticket ID.'
        );
    }

    // ---------------------------------------------------------------------
    // Group F -- WCAG citation present (justification + audit trail)
    // ---------------------------------------------------------------------

    #[Test]
    public function wcag_citation_documented_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/WCAG\s*2\.5\.5/',
            $this->sourceCss,
            'AI-1148: comment block MUST cite WCAG 2.5.5 Target Size as the justification for the 44px floor.'
        );
    }
}
