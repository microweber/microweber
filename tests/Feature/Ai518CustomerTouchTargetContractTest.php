<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-518 — Customer (Profile + Public header) touch-target floors per the
 * agent-test mobile audit (2026-05-14, viewport 390x844 iPhone 13).
 *
 * Audit measurements:
 *   - Remember-me checkbox (Filament profile-panel login form) 13x13 -> 44h row
 *   - CONTACT US header button (Bootstrap `<module type="btn" id="header-layout-btn">`)
 *     137x38 -> 44h height
 *
 * Fix spans TWO surfaces with TWO different CSS files / scopes:
 *   1. `body.fi-panel-profile` rules in
 *      `packages/microweber-filament-theme/.../mobile-touch.css` — Webpack-
 *      built. Mirrors the AI-517 `:has(.fi-checkbox-input)` wrapper-label
 *      pattern.
 *   2. Header-context rules in
 *      `Templates/Bootstrap/resources/assets/css/public-touch.css` — Vite-
 *      built. Targets `#header-layout-btn` + `.templates-top-header-menu
 *      .btn.btn-sm` so content-area `.btn-sm` instances are unaffected.
 *
 * Per project memory `feedback_testing`: file-system reads only, no DB /
 * Filament boot.
 */
class Ai518CustomerTouchTargetContractTest extends TestCase
{
    private const MOBILE_TOUCH_CSS = 'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css';
    private const PUBLIC_TOUCH_CSS = 'Templates/Bootstrap/resources/assets/css/public-touch.css';

    private string $mobileTouchCss;
    private string $publicTouchCss;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mobileTouchCss = file_get_contents(base_path(self::MOBILE_TOUCH_CSS));
        $this->publicTouchCss = file_get_contents(base_path(self::PUBLIC_TOUCH_CSS));
    }

    private function profileBlock(): string
    {
        // The AI-518 profile-panel rules live inside the AI-517 block's
        // closing brace expansion; locate the FIRST `body.fi-panel-profile`
        // occurrence and slice from there.
        $start = strpos($this->mobileTouchCss, 'body.fi-panel-profile');
        $this->assertNotFalse(
            $start,
            'mobile-touch.css must contain at least one body.fi-panel-profile rule (AI-518)'
        );
        return substr($this->mobileTouchCss, $start);
    }

    private function contactUsBlock(): string
    {
        $start = strpos($this->publicTouchCss, 'AI-518');
        $this->assertNotFalse(
            $start,
            'public-touch.css must contain the AI-518 marker comment'
        );
        return substr($this->publicTouchCss, $start);
    }

    #[Test]
    public function ai518_marker_comment_present_in_mobile_touch_css(): void
    {
        // Pin the dispatch attribution so future archaeologists can
        // trace back to the agent-test mobile audit + PM sequential
        // dispatch.
        $this->assertStringContainsString('AI-518', $this->mobileTouchCss);
        $this->assertStringContainsString('Profile panel', $this->mobileTouchCss);
        $this->assertStringContainsString('FilamentProfilePanelProvider', $this->mobileTouchCss);
    }

    #[Test]
    public function ai518_marker_comment_present_in_public_touch_css(): void
    {
        $this->assertStringContainsString('AI-518', $this->publicTouchCss);
        $this->assertStringContainsString('header-layout-btn', $this->publicTouchCss);
    }

    #[Test]
    public function profile_checkbox_field_wrapper_floors_44_height(): void
    {
        $block = $this->profileBlock();
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-profile\s+\.fi-fo-field-wrp:has\(\.fi-checkbox-input\)\s*\{[^}]*min-height:\s*44px;[^}]*\}/s',
            $block,
            'profile panel: field-wrapper containing `.fi-checkbox-input` must floor 44h'
        );
    }

    #[Test]
    public function profile_checkbox_label_row_floors_44_height_and_inline_flex(): void
    {
        $block = $this->profileBlock();
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-profile\s+label:has\([^)]*\.fi-checkbox-input\)[^{]*\{[^}]*min-height:\s*44px;[^}]*display:\s*inline-flex;[^}]*align-items:\s*center;[^}]*\}/s',
            $block,
            'profile panel: label containing `.fi-checkbox-input` must floor 44h + inline-flex centring'
        );
    }

    #[Test]
    public function contact_us_button_floors_44_height_with_stable_selector(): void
    {
        $block = $this->contactUsBlock();
        // Pin both the id-specific selector AND the header-context fallback
        // so the rule survives a skin that drops the id.
        $this->assertStringContainsString('#header-layout-btn.btn', $block);
        $this->assertStringContainsString('.templates-top-header-menu', $block);
        $this->assertMatchesRegularExpression(
            '/#header-layout-btn[^{]*\{[^}]*min-height:\s*44px;[^}]*\}/s',
            $block,
            'CONTACT US button rule must declare min-height: 44px'
        );
    }

    #[Test]
    public function ai518_rules_live_inside_the_touch_viewport_media_query(): void
    {
        // Both files share the canonical touch-viewport media query.
        // For mobile-touch.css the AI-518 block is appended to the AI-517
        // media block (no separate @media); verify the profile rules are
        // text-wise after the same @media opener that begins the AI-517 block.
        $ai517MediaStart = strpos($this->mobileTouchCss, '@media (max-width: 1023.98px), (hover: none) and (pointer: coarse) {');
        $this->assertNotFalse($ai517MediaStart);
        $profilePos = strpos($this->mobileTouchCss, 'body.fi-panel-profile');
        $this->assertNotFalse($profilePos);
        $this->assertGreaterThan(
            $ai517MediaStart,
            $profilePos,
            'profile panel rules must sit inside the AI-517 touch-viewport @media block'
        );

        // For public-touch.css the AI-518 block sits inside the canonical
        // touch-viewport @media that opens at line 669. Verify (a) the
        // canonical opener precedes the AI-518 marker, and (b) the AI-518
        // rule bodies don't open a fresh @media of their own. Bound the
        // AI-518 slice to JUST the rule by closing-brace count rather
        // than running to EOF.
        $touchMediaPos = strpos(
            $this->publicTouchCss,
            '@media (max-width: 1023.98px), (hover: none) and (pointer: coarse)'
        );
        $this->assertNotFalse($touchMediaPos, 'public-touch.css must carry the canonical touch-viewport @media opener');
        $ai518MarkerPos = strpos($this->publicTouchCss, 'AI-518');
        $this->assertGreaterThan(
            $touchMediaPos,
            $ai518MarkerPos,
            'AI-518 marker must appear AFTER the canonical touch-viewport @media opener so it inherits the touch scope'
        );
        // Bound the AI-518 slice from marker to the next closing brace
        // ending a rule body (the rule terminator after the selector list).
        $remaining = substr($this->publicTouchCss, $ai518MarkerPos);
        $blockEnd = strpos($remaining, "\n    }\n");
        $this->assertNotFalse($blockEnd, 'AI-518 public-touch block must terminate cleanly');
        $ai518PublicBlock = substr($remaining, 0, $blockEnd + 6);
        $this->assertStringNotContainsString(
            '@media',
            $ai518PublicBlock,
            'AI-518 rule body in public-touch.css must NOT open its own @media — it inherits the touch-viewport block'
        );
    }

    #[Test]
    public function admin_and_checkout_panels_are_not_affected_by_ai518_profile_rules(): void
    {
        // Guard: NO profile-panel rule may target `body.fi-panel-admin`
        // or `body.fi-panel-checkout`. Bound the AI-518 profile-panel
        // sub-block from the AI-518 marker comment to the start of the
        // next AI-NNN block-comment marker (or to the enclosing @media
        // closing brace if no further block exists). Per LESSONS.md
        // 2026-05-14 — slice to the rule terminator, not to a larger
        // block that may grow underneath the marker.
        $ai518MarkerStart = strpos($this->mobileTouchCss, 'AI-518 — Customer / Profile panel');
        $this->assertNotFalse($ai518MarkerStart, 'AI-518 profile-panel marker comment must exist');
        $remaining = substr($this->mobileTouchCss, $ai518MarkerStart);

        // Prefer bounding to the next AI- block-comment marker; fall back
        // to the @media closing brace if no further AI- block exists.
        $nextAiMarker = strpos($remaining, '/* ============================================================
     * AI-', 1);
        if ($nextAiMarker !== false) {
            $blockEnd = $nextAiMarker;
        } else {
            $blockEnd = strpos($remaining, "\n}\n");
            $this->assertNotFalse($blockEnd, 'AI-518 profile-panel block must terminate cleanly');
        }
        $ai518Block = substr($remaining, 0, $blockEnd);

        $this->assertStringNotContainsString(
            'body.fi-panel-admin',
            $ai518Block,
            'AI-518 profile rules must stay scoped to body.fi-panel-profile — never leak into admin'
        );
        $this->assertStringNotContainsString(
            'body.fi-panel-checkout',
            $ai518Block,
            'AI-518 profile rules must stay scoped to body.fi-panel-profile — never leak into checkout'
        );
    }
}
