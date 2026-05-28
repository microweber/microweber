<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-28-7e3b4c / AI-1156 — Modal footer secondary buttons
 * 42px → 44px at mobile (WCAG 2.5.5 Target Size compliance).
 * Jira: https://microweber.atlassian.net/browse/AI-1156
 *
 * Pre-fix: Filament v5 renders modal footer action buttons (Cancel,
 * secondary actions, link-style fi-btn anchors) at 42px — h-9 (36px
 * Tailwind utility) + 3px top/bottom padding = 42px rendered height.
 * 2px short of the 44 CSS-pixel floor that AI-517 / AI-518 / AI-519
 * / AI-1148 / AI-1192 / AI-1218 enforce across admin + checkout +
 * Live Edit interactive surfaces.
 *
 * Fix: append a new @media (max-width: 1023.98px), (hover: none) and
 * (pointer: coarse) block to mobile-touch.css that pins
 * .fi-modal-footer .fi-btn (+ .fi-modal-footer-actions .fi-btn
 * variant) to min-height: 44px !important + min-width: 44px on
 * BOTH the admin AND checkout panel scopes. `!important` defeats
 * Filament's h-9 utility specificity.
 *
 * Source-of-truth: packages/microweber-filament-theme/resources/
 * assets/css/microweber/mobile-touch.css (Webpack pipeline).
 * Served bundle: public/vendor/microweber-packages/microweber-
 * filament-theme/build/microweber-filament-theme.css.
 *
 * Selector-self-match guard: setUp() pre-strips CSS block comments
 * before any absence assertion (uniformity-rule from task-7aa48a).
 */
class Admin7e3b4cAI1156ModalFooterMobileTouchContractTest extends TestCase
{
    private const SOURCE_CSS = 'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css';

    private const SERVED_CSS = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    private string $sourceCss;

    private string $sourceCssStripped;

    private string $servedCss;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sourceCss = (string) file_get_contents(base_path(self::SOURCE_CSS));
        $this->sourceCssStripped = $this->stripCssComments($this->sourceCss);
        $this->servedCss = (string) file_get_contents(base_path(self::SERVED_CSS));
    }

    private function stripCssComments(string $source): string
    {
        return (string) preg_replace('~/\*[\s\S]*?\*/~', '', $source);
    }

    // ---------------------------------------------------------------------
    // Group A -- Source rule pins admin scope at 44px touch target
    // ---------------------------------------------------------------------

    #[Test]
    public function source_pins_admin_modal_footer_btn_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-modal-footer\s+\.fi-btn[\s\S]{0,400}?min-height:\s*44px\s*!important/',
            $this->sourceCssStripped,
            'AI-1156: body.fi-panel-admin .fi-modal-footer .fi-btn MUST pin min-height: 44px !important to meet WCAG 2.5.5 Target Size on mobile.'
        );
    }

    #[Test]
    public function source_pins_admin_modal_footer_actions_btn_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-modal-footer-actions\s+\.fi-btn[\s\S]{0,400}?min-height:\s*44px\s*!important/',
            $this->sourceCssStripped,
            'AI-1156: body.fi-panel-admin .fi-modal-footer-actions .fi-btn (legacy footer-actions wrapper) MUST also pin min-height: 44px !important.'
        );
    }

    #[Test]
    public function source_pins_admin_modal_footer_btn_min_width_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-modal-footer[a-z\-]*\s+\.fi-btn[\s\S]{0,400}?min-width:\s*44px\s*!important/',
            $this->sourceCssStripped,
            'AI-1156: modal footer buttons MUST also declare min-width: 44px !important so icon-only Cancel variants meet the WCAG 44x44 floor on both axes.'
        );
    }

    // ---------------------------------------------------------------------
    // Group B -- Source rule pins checkout scope at 44px touch target
    // ---------------------------------------------------------------------

    #[Test]
    public function source_pins_checkout_modal_footer_btn_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-checkout\s+\.fi-modal-footer\s+\.fi-btn[\s\S]{0,400}?min-height:\s*44px\s*!important/',
            $this->sourceCssStripped,
            'AI-1156: checkout panel modal footer buttons MUST also pin min-height: 44px !important — touch-target floor is enforced cross-surface (admin + checkout).'
        );
    }

    // ---------------------------------------------------------------------
    // Group C -- @media gate present (mobile + touch viewports only)
    // ---------------------------------------------------------------------

    #[Test]
    public function rule_is_gated_inside_mobile_touch_media_query(): void
    {
        // AI-1156 rule MUST live inside a @media (max-width: 1023.98px)
        // OR @media (hover: none) and (pointer: coarse) block. Slice
        // out the AI-1156 region by anchor, then assert it sits inside
        // a @media block (count opening braces vs closing braces from
        // the AI-1156 anchor backwards to the nearest @media).
        $anchor = strpos($this->sourceCss, 'task-2026-05-28-7e3b4c');
        $this->assertNotFalse($anchor, 'AI-1156 task-id anchor must be present.');

        $beforeAnchor = substr($this->sourceCss, 0, $anchor);
        $lastMediaPos = strrpos($beforeAnchor, '@media');
        $this->assertNotFalse($lastMediaPos, 'AI-1156 rule must be preceded by an @media declaration.');

        $mediaDeclaration = substr($this->sourceCss, $lastMediaPos, 200);
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*1023\.98px\)|\(hover:\s*none\)\s*and\s*\(pointer:\s*coarse\)/',
            $mediaDeclaration,
            'AI-1156: the modal footer 44px rule MUST be gated inside a @media (max-width: 1023.98px), (hover: none) and (pointer: coarse) block — desktop fine-pointer users keep the 36-42px compact Filament default.'
        );
    }

    // ---------------------------------------------------------------------
    // Group D -- Served bundle (Webpack-built CSS) carries the fix
    // ---------------------------------------------------------------------

    #[Test]
    public function served_bundle_carries_admin_modal_footer_44px_rule(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-modal-footer\s+\.fi-btn[\s\S]{0,400}?min-height:\s*44px\s*!important/',
            $this->servedCss,
            'AI-1156: served microweber-filament-theme.css MUST carry the admin modal footer 44px rule — run `cd packages/microweber-filament-theme && npm run build` if absent.'
        );
    }

    #[Test]
    public function served_bundle_carries_checkout_modal_footer_44px_rule(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-checkout\s+\.fi-modal-footer\s+\.fi-btn[\s\S]{0,400}?min-height:\s*44px\s*!important/',
            $this->servedCss,
            'AI-1156: served microweber-filament-theme.css MUST carry the checkout modal footer 44px rule.'
        );
    }

    // ---------------------------------------------------------------------
    // Group E -- task-id + AI-1156 markers (audit grep contract)
    // ---------------------------------------------------------------------

    #[Test]
    public function task_id_marker_present_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-7e3b4c',
            $this->sourceCss,
            'AI-1156: mobile-touch.css MUST carry the AI-1156 task-id marker for cross-surface audit grep.'
        );
        $this->assertStringContainsString(
            'AI-1156',
            $this->sourceCss,
            'AI-1156: mobile-touch.css MUST cite the AI-1156 ticket ID.'
        );
    }

    // ---------------------------------------------------------------------
    // Group F -- WCAG citation present (justification + audit trail)
    // ---------------------------------------------------------------------

    #[Test]
    public function wcag_citation_documented_in_source(): void
    {
        // Slice 1500 chars after the AI-1156 anchor so the WCAG citation
        // is asserted in the new rule's docblock, not elsewhere in the file.
        $anchor = strpos($this->sourceCss, 'task-2026-05-28-7e3b4c');
        $this->assertNotFalse($anchor, 'AI-1156 task-id anchor must be present.');

        $slice = substr($this->sourceCss, $anchor, 1500);

        $this->assertMatchesRegularExpression(
            '/WCAG\s*2\.5\.5/',
            $slice,
            'AI-1156: the AI-1156 rule docblock MUST cite WCAG 2.5.5 Target Size as the justification for the 44px floor.'
        );
    }
}
