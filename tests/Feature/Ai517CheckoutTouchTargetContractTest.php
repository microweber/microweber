<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-517 — Public checkout (`body.fi-panel-checkout`) touch-target
 * floors per the agent-test mobile audit (2026-05-14).
 *
 * Audit measurements:
 *   - Review Edit buttons (`.review-edit-btn`)          24x20  -> 44x44
 *   - Terms checkbox label row (Filament Checkbox)      18x18  -> 44h
 *   - Place Order button (Filament Action success/lg)  359x42  -> 44h
 *
 * All three rules sit inside the existing touch-viewport media query
 * (max-width 1023.98 OR pointer:coarse) scoped to `body.fi-panel-checkout`
 * — admin (`body.fi-panel-admin`) is unaffected.
 *
 * Style: file-system reads only, no DB / Filament boot (per project
 * memory `feedback_testing`).
 */
class Ai517CheckoutTouchTargetContractTest extends TestCase
{
    private const MOBILE_TOUCH_CSS = 'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css';

    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = file_get_contents(base_path(self::MOBILE_TOUCH_CSS));
    }

    private function ai517Block(): string
    {
        $start = strpos($this->css, 'AI-517');
        $this->assertNotFalse($start, 'mobile-touch.css must contain the AI-517 marker comment.');
        return substr($this->css, $start);
    }

    #[Test]
    public function ai517_marker_comment_is_present_and_traceable(): void
    {
        $block = $this->ai517Block();
        // Pin the dispatch attribution so future archaeologists can
        // trace back to the agent-test mobile audit + PM parallel-track
        // dispatch.
        $this->assertStringContainsString('agent-test mobile audit', $block);
        $this->assertStringContainsString('body.fi-panel-checkout', $block);
    }

    #[Test]
    public function ai517_rules_live_inside_the_touch_viewport_media_query(): void
    {
        $block = $this->ai517Block();
        // The block must open with the canonical touch-viewport media
        // query so desktop / fine-pointer surfaces are untouched.
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*1023\.98px\s*\),\s*\(\s*hover:\s*none\s*\)\s*and\s*\(\s*pointer:\s*coarse\s*\)\s*\{/',
            $block,
            'AI-517 rules must be wrapped by the touch-viewport media query'
        );
    }

    #[Test]
    public function review_edit_button_carries_explicit_44_floor_and_centering(): void
    {
        $block = $this->ai517Block();
        // Selector + the full declaration body. Pin BOTH min-width AND
        // min-height plus inline-flex centring so the larger hit-area
        // keeps the text visually centred inside the 44x44 box.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-checkout\s+\.review-edit-btn\s*\{[^}]*min-width:\s*44px;[^}]*min-height:\s*44px;[^}]*display:\s*inline-flex;[^}]*align-items:\s*center;[^}]*justify-content:\s*center;[^}]*\}/s',
            $block,
            'Review Edit button rule must declare min-width: 44px + min-height: 44px + inline-flex centring'
        );
    }

    #[Test]
    public function terms_checkbox_label_row_floors_44_height(): void
    {
        $block = $this->ai517Block();
        // Two selectors — the field wrapper that contains a checkbox
        // input, and the label that contains it. Both must reach 44h.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-checkout\s+\.fi-fo-field-wrp:has\(\.fi-checkbox-input\)\s*\{[^}]*min-height:\s*44px;[^}]*\}/s',
            $block,
            'Field-wrapper containing `.fi-checkbox-input` must floor 44h'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-checkout\s+label:has\([^)]*\.fi-checkbox-input\)[^{]*\{[^}]*min-height:\s*44px;[^}]*\}/s',
            $block,
            'Label that contains `.fi-checkbox-input` must floor 44h'
        );
    }

    #[Test]
    public function place_order_success_button_floors_44_height_with_important(): void
    {
        $block = $this->ai517Block();
        // Both selector variants (bare button + wizard-actions wrapper)
        // plus min-height: 44px !important to beat the Filament size=lg
        // inline-style cascade.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-checkout\s+button\.fi-btn\.fi-color-success[^{]*\{[^}]*min-height:\s*44px\s*!important;[^}]*\}/s',
            $block,
            'Place Order success-color button must floor 44h with !important'
        );
    }

    #[Test]
    public function admin_panel_is_not_affected_by_ai517_rules(): void
    {
        // Guard: NO rule inside the AI-517 block may target
        // `body.fi-panel-admin`. The block is checkout-scoped only.
        $block = $this->ai517Block();
        // Strip the AI-517 marker comment header (which mentions
        // `body.fi-panel-admin` once for context); look at the rule
        // bodies only by slicing from the first `@media`.
        $mediaStart = strpos($block, '@media');
        $this->assertNotFalse($mediaStart);
        $rules = substr($block, $mediaStart);
        $this->assertStringNotContainsString(
            'body.fi-panel-admin',
            $rules,
            'AI-517 rules must stay scoped to body.fi-panel-checkout — never leak into the admin panel'
        );
    }
}
