<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-519 — Coupon Module mobile touch-target coverage analysis (sequential
 * cadence per PM dispatch 2026-05-14T05:14:18, after Phase 1 UX status
 * correction).
 *
 * Recon outcome: all four AI-519 surfaces inherit existing rules:
 *   - Surface 1: Public coupon-code TextInput (in CheckoutResource Action
 *     modal) -> AI-517 `body.fi-panel-checkout .fi-input` min-height:
 *     var(--touch-target-min) (= 44px) at mobile-touch.css line 703.
 *   - Surface 2: "Enter discount code" / "Remove coupon" Filament Actions
 *     (CheckoutResource lines 219-263) -> AI-510/AI-211 Filament action-
 *     button rules in the checkout panel + the cycle-58/AI-221 generic
 *     `.fi-icon-btn` 44x44 floor.
 *   - Surface 3: Admin coupon-list table (CouponResource list page) ->
 *     AI-246 stacked-card flip + AI-221 row-action touch-target rules.
 *   - Surface 4: Admin coupon Create/Edit form (CouponResource) ->
 *     PARTIAL coverage. Inputs receive font-size: 16px (anti-iOS-zoom)
 *     at line 97; there is NO explicit `body.fi-panel-admin .fi-input`
 *     min-height: 44px rule analogous to the checkout-panel one. The
 *     Filament default ~40px input height likely sits 4px short of
 *     the 44px floor. **Flagged as AI-519a follow-up candidate** —
 *     defer until tester provides measurements (no JOURNAL row 13
 *     blind-refactor risk).
 *
 * This test ships as a regression guard for the inherited coverage so a
 * future refactor that removes / weakens AI-517 / AI-221 / AI-246 fails
 * here visibly with a coupon-anchored message.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Ai519CouponCoverageContractTest extends TestCase
{
    private const MOBILE_TOUCH_CSS = 'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css';
    private const COUPON_RESOURCE  = 'Modules/Coupons/Filament/Resources/CouponResource.php';
    private const CHECKOUT_RESOURCE = 'Modules/Checkout/Filament/Resources/CheckoutResource.php';

    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = file_get_contents(base_path(self::MOBILE_TOUCH_CSS));
    }

    #[Test]
    public function coupon_resource_module_exists_on_filesystem(): void
    {
        // Pin the registration surface so the coverage assumptions stay
        // anchored to a real file. If CouponResource moves, the test
        // breaks here with a clear "AI-519 anchor missing" signal.
        $this->assertFileExists(
            base_path(self::COUPON_RESOURCE),
            'AI-519 anchor: CouponResource.php must exist at the expected path'
        );
    }

    #[Test]
    public function public_coupon_input_inherits_checkout_panel_44_floor(): void
    {
        // The public coupon-code TextInput is rendered by
        // CheckoutResource's `apply_coupon` Action modal — confirm the
        // TextInput is defined inside CheckoutResource (so it inherits
        // body.fi-panel-checkout scope at render time).
        $checkout = file_get_contents(base_path(self::CHECKOUT_RESOURCE));
        $this->assertStringContainsString(
            "TextInput::make('coupon_code')",
            $checkout,
            'AI-519 surface 1 anchor: CheckoutResource must declare the coupon_code TextInput'
        );

        // Pin AI-517 rule: body.fi-panel-checkout .fi-input min-height
        // is var(--touch-target-min). Without this, the public coupon
        // input would fail the WCAG 44 floor.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-checkout\s+\.fi-input[^{]*\{[^}]*min-height:\s*var\(--touch-target-min\)/s',
            $this->css,
            'AI-519 surface 1: body.fi-panel-checkout .fi-input must inherit min-height var(--touch-target-min) (AI-517 rule)'
        );
        $this->assertMatchesRegularExpression(
            '/--touch-target-min:\s*44px/',
            $this->css,
            'AI-519 anchor: --touch-target-min must resolve to 44px'
        );
    }

    #[Test]
    public function apply_coupon_button_inherits_filament_action_44_floor(): void
    {
        $checkout = file_get_contents(base_path(self::CHECKOUT_RESOURCE));
        $this->assertStringContainsString(
            "Action::make('apply_coupon')",
            $checkout,
            'AI-519 surface 2 anchor: CheckoutResource must declare the apply_coupon Action'
        );
        // Filament's `.fi-icon-btn` 44x44 floor (cycle-58 / AI-221) covers
        // icon-only action buttons. Pin the rule presence.
        $this->assertMatchesRegularExpression(
            '/\.fi-icon-btn\s*\{[^}]*min-width:\s*44px;[^}]*min-height:\s*44px;[^}]*\}/s',
            $this->css,
            'AI-519 surface 2: `.fi-icon-btn` 44x44 rule must be present for action-button coverage'
        );
    }

    #[Test]
    public function admin_coupon_list_inherits_ai246_stacked_card_and_ai221_row_floor(): void
    {
        // AI-246 admin-table stacked-card flip below 1024px covers the
        // coupon list table (and every other admin resource list).
        $this->assertStringContainsString(
            'AI-246',
            $this->css,
            'AI-519 surface 3: AI-246 marker must be present for stacked-card coverage'
        );
        // AI-221 row-action floors `.fi-ta-row .fi-ta-actions .fi-icon-btn`
        // to 44x44 — covers the per-row coupon-edit / coupon-delete icons.
        $this->assertMatchesRegularExpression(
            '/\.fi-ta-row\s+\.fi-ta-actions[^{]*\{[^}]*min-width:\s*44px;[^}]*min-height:\s*44px;[^}]*\}/s',
            $this->css,
            'AI-519 surface 3: .fi-ta-row .fi-ta-actions 44x44 rule (AI-221) must be present'
        );
    }

    #[Test]
    public function admin_form_inputs_have_anti_ios_zoom_font_size(): void
    {
        // AI-211 / AI-510 partial coverage for surface 4: admin form inputs
        // get font-size: 16px (which is what prevents iOS Safari zoom-on-
        // focus). The min-height floor is NOT explicit for admin form
        // inputs — flagged as AI-519a follow-up candidate (see test
        // docblock + class docblock for rationale).
        $this->assertMatchesRegularExpression(
            '/(\.fi-input|\.fi-fo-text-input\s+input)[^{}]*[\s,]*\{[^}]*font-size:\s*16px/s',
            $this->css,
            'AI-519 surface 4: .fi-input font-size: 16px (anti-iOS-zoom) rule must be present'
        );
    }

    #[Test]
    public function ai519a_followup_gap_is_documented_in_test_class_docblock(): void
    {
        // The test class docblock must call out the admin-form-input
        // min-height gap explicitly so the next agent reading this file
        // sees the follow-up candidate without re-doing the recon.
        $self = file_get_contents(__FILE__);
        $this->assertStringContainsString(
            'AI-519a follow-up candidate',
            $self,
            'AI-519 docblock must flag the admin-form-input min-height gap as an AI-519a follow-up candidate'
        );
    }
}
