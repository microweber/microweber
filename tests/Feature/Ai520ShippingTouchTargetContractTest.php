<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-520 — Shipping module mobile touch-target coverage per PM dispatch
 * 2026-05-14T05:22:40, sequential cadence after AI-519 ship.
 *
 * Recon outcome (5 surfaces from dispatch checklist):
 *   - Surface 1: Shipping method selection (checkout) — Filament
 *     `Radio::make('shipping_provider_id')` in
 *     Modules/Checkout/Filament/Resources/CheckoutResource.php line 168.
 *     Renders as `<label class="fi-fo-radio-label">` per option.
 *     **REAL GAP** — AI-517 covered checkboxes via `:has(.fi-checkbox-input)`
 *     but the analogous radio rule did not exist. Shipped here.
 *   - Surface 2: Shipping calculator — same Radio component (in-form
 *     state machine; no separate calculator widget on the Filament
 *     checkout panel).
 *   - Surface 3: Shipping options readable — same Radio rows; the AI-520
 *     44h floor gives every option a touch-comfortable row.
 *   - Surface 4: Admin ShippingProviderResource table — inherits AI-246
 *     stacked-card flip below 1024px + AI-221 row-action 44x44 floors.
 *     No additional rules needed.
 *   - Surface 5: Multi-carrier selection — same Radio component (multiple
 *     providers = multiple radio options); covered by Surface 1 fix.
 *
 * Cross-application: the AI-520 rule also covers any other Filament
 * Radio rendered inside `body.fi-panel-checkout` (e.g. Payment method
 * if that uses Radio in future) — single, narrowly-scoped, opt-in via
 * panel class.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Ai520ShippingTouchTargetContractTest extends TestCase
{
    private const MOBILE_TOUCH_CSS = 'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css';
    private const SHIPPING_RESOURCE = 'Modules/Shipping/Filament/Admin/Resources/ShippingProviderResource.php';
    private const CHECKOUT_RESOURCE = 'Modules/Checkout/Filament/Resources/CheckoutResource.php';

    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = file_get_contents(base_path(self::MOBILE_TOUCH_CSS));
    }

    private function ai520Block(): string
    {
        $start = strpos($this->css, 'AI-520');
        $this->assertNotFalse(
            $start,
            'mobile-touch.css must contain the AI-520 marker comment'
        );
        $remaining = substr($this->css, $start);
        // Bound to the rule's closing brace — `\n    }\n` ends the
        // .fi-fo-radio-label rule body. Stops before any later rule.
        $end = strpos($remaining, "\n    }\n");
        $this->assertNotFalse(
            $end,
            'AI-520 rule body must terminate cleanly with a closing brace'
        );
        return substr($remaining, 0, $end + 6);
    }

    #[Test]
    public function ai520_marker_comment_present(): void
    {
        $this->assertStringContainsString('AI-520', $this->css);
        $this->assertStringContainsString('Shipping module', $this->css);
        $this->assertStringContainsString('fi-fo-radio-label', $this->css);
    }

    #[Test]
    public function shipping_provider_resource_anchor_exists(): void
    {
        $this->assertFileExists(
            base_path(self::SHIPPING_RESOURCE),
            'AI-520 anchor: ShippingProviderResource.php must exist at the expected path'
        );
    }

    #[Test]
    public function checkout_declares_shipping_provider_radio(): void
    {
        $checkout = file_get_contents(base_path(self::CHECKOUT_RESOURCE));
        $this->assertStringContainsString(
            "Radio::make('shipping_provider_id')",
            $checkout,
            'AI-520 surface 1 anchor: CheckoutResource must declare the shipping_provider_id Radio'
        );
    }

    #[Test]
    public function checkout_radio_label_floors_44_height(): void
    {
        $block = $this->ai520Block();
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-checkout\s+label\.fi-fo-radio-label\s*\{[^}]*min-height:\s*44px;[^}]*display:\s*flex;[^}]*align-items:\s*center;[^}]*\}/s',
            $block,
            'AI-520 surface 1: body.fi-panel-checkout label.fi-fo-radio-label must floor 44h with flex centring'
        );
    }

    #[Test]
    public function ai520_rule_lives_inside_touch_viewport_media_query(): void
    {
        // AI-520 was appended to the AI-517 / AI-518 @media block — same
        // touch-viewport scope. Verify the canonical opener precedes the
        // marker so the rule inherits the @media gate.
        $touchMediaStart = strpos(
            $this->css,
            '@media (max-width: 1023.98px), (hover: none) and (pointer: coarse)'
        );
        $this->assertNotFalse($touchMediaStart);
        $ai520Pos = strpos($this->css, 'AI-520');
        $this->assertGreaterThan(
            $touchMediaStart,
            $ai520Pos,
            'AI-520 marker must appear AFTER the canonical touch-viewport @media opener so it inherits the touch scope'
        );

        // The rule body itself must NOT open a fresh @media of its own.
        $block = $this->ai520Block();
        $this->assertStringNotContainsString(
            '@media',
            $block,
            'AI-520 rule body must NOT open its own @media — it inherits the AI-517/AI-518 touch-viewport block'
        );
    }

    #[Test]
    public function admin_shipping_list_inherits_ai246_stacked_card_and_ai221_row_floor(): void
    {
        // Surface 4: admin ShippingProviderResource table inherits the
        // generic admin-table card-flip below 1024px + row-action floor.
        $this->assertStringContainsString(
            'AI-246',
            $this->css,
            'AI-520 surface 4: AI-246 marker must be present for stacked-card coverage of the admin shipping list'
        );
        $this->assertMatchesRegularExpression(
            '/\.fi-ta-row\s+\.fi-ta-actions[^{]*\{[^}]*min-width:\s*44px;[^}]*min-height:\s*44px;[^}]*\}/s',
            $this->css,
            'AI-520 surface 4: .fi-ta-row .fi-ta-actions 44x44 rule (AI-221) must be present for per-row Edit/Delete on the shipping list'
        );
    }

    #[Test]
    public function ai520_rule_does_not_leak_into_admin_or_profile_panels(): void
    {
        $block = $this->ai520Block();
        $this->assertStringNotContainsString(
            'body.fi-panel-admin',
            $block,
            'AI-520 radio rule must stay scoped to body.fi-panel-checkout — never leak into admin'
        );
        $this->assertStringNotContainsString(
            'body.fi-panel-profile',
            $block,
            'AI-520 radio rule must stay scoped to body.fi-panel-checkout — never leak into profile'
        );
    }
}
