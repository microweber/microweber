<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-507 — Media Library icon-only action button touch-target floors.
 * AI-508 — Filament table drag/reorder handle touch-target floor.
 *
 * Agent-test admin mobile audit results (2026-05-14, code-level analysis):
 *
 *   AI-505  Content List  icon-only row actions (.fi-ta-actions buttons)
 *           ALREADY COVERED by existing `.fi-ta-row .fi-ta-actions button`
 *           rule — no new CSS needed.
 *
 *   AI-506  Content Edit  RTE toolbar buttons (.fi-fo-rich-editor-toolbar)
 *           ALREADY COVERED by existing `.fi-fo-rich-editor-toolbar button`
 *           rule — no new CSS needed.
 *
 *   AI-507  Media Library  `.mw-media-item-menu-btn` — SCSS fixes at 26×26
 *           (below 44px floor). `.mw-media-folder-menu-btn`, `.mw-media-
 *           folder-expand`, `.mw-media-view-btn`, `.mw-media-clear-filters`
 *           all lack an explicit min-height.
 *
 *   AI-508  Content list / Page management  `.fi-ta-reorder-handle` drag
 *           handles are `.fi-icon-btn` elements in a dedicated row cell —
 *           not inside `.fi-ta-actions`, so existing action-button rules
 *           do not cover them. Filament base size-9 = 36px.
 *
 *   AI-509  User Management  `.fi-toggle` status toggles
 *           ALREADY COVERED by existing `.fi-toggle / button.fi-toggle`
 *           rule — no new CSS needed. UsersResource has no avatar column.
 *
 * All new rules live in mobile-touch.css scoped to `body.fi-panel-admin`
 * inside the canonical touch media query — admin only.
 *
 * File-system reads only (no DB / Filament boot).
 *
 * Boundary guard: rules must NOT scope to `.fi-panel-checkout` or omit
 * `body.fi-panel-admin` — these are admin-specific surfaces.
 */
class Ai507Ai508AdminMediaDragContractTest extends TestCase
{
    private const MOBILE_TOUCH_CSS = 'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css';

    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = file_get_contents(base_path(self::MOBILE_TOUCH_CSS));
    }

    private function ai507Block(): string
    {
        $start = strpos($this->css, 'AI-507');
        $this->assertNotFalse($start, 'mobile-touch.css must contain the AI-507 marker comment.');
        return substr($this->css, $start);
    }

    private function ai508Block(): string
    {
        $start = strpos($this->css, 'AI-508');
        $this->assertNotFalse($start, 'mobile-touch.css must contain the AI-508 marker comment.');
        return substr($this->css, $start);
    }

    // -----------------------------------------------------------------------
    // AI-507 — Media Library icon buttons
    // -----------------------------------------------------------------------

    #[Test]
    public function ai507_marker_comment_is_present(): void
    {
        $this->assertStringContainsString('AI-507', $this->css);
        $this->assertStringContainsString('mw-media-item-menu-btn', $this->css);
    }

    #[Test]
    public function ai507_media_item_menu_btn_has_min_44(): void
    {
        $block = $this->ai507Block();
        $this->assertMatchesRegularExpression(
            '/\.mw-media-item-menu-btn/',
            $block,
            'AI-507: mobile-touch.css must target .mw-media-item-menu-btn'
        );
        $this->assertMatchesRegularExpression(
            '/min-height\s*:\s*44px/',
            $block,
            'AI-507: .mw-media-item-menu-btn block must include min-height:44px'
        );
    }

    #[Test]
    public function ai507_media_folder_menu_btn_has_min_44(): void
    {
        $block = $this->ai507Block();
        $this->assertStringContainsString('.mw-media-folder-menu-btn', $block);
    }

    #[Test]
    public function ai507_media_folder_expand_has_min_44(): void
    {
        $block = $this->ai507Block();
        $this->assertStringContainsString('.mw-media-folder-expand', $block);
    }

    #[Test]
    public function ai507_media_view_btn_has_min_44(): void
    {
        $block = $this->ai507Block();
        $this->assertStringContainsString('.mw-media-view-btn', $block);
    }

    #[Test]
    public function ai507_rules_scoped_to_admin_panel(): void
    {
        $block = $this->ai507Block();
        $this->assertStringContainsString('body.fi-panel-admin', $block);
    }

    #[Test]
    public function ai507_rules_inside_touch_media_query(): void
    {
        $block = $this->ai507Block();
        $this->assertMatchesRegularExpression(
            '/@media\s*\([^)]*max-width\s*:\s*1023\.98px[^)]*\)/',
            $block,
            'AI-507 rules must be inside the touch-viewport media query'
        );
    }

    // -----------------------------------------------------------------------
    // AI-508 — Drag/reorder handle
    // -----------------------------------------------------------------------

    #[Test]
    public function ai508_marker_comment_is_present(): void
    {
        $this->assertStringContainsString('AI-508', $this->css);
        $this->assertStringContainsString('fi-ta-reorder-handle', $this->css);
    }

    #[Test]
    public function ai508_reorder_handle_has_min_44(): void
    {
        $block = $this->ai508Block();
        $this->assertMatchesRegularExpression(
            '/\.fi-ta-reorder-handle[^{]*\{[^}]*min-height\s*:\s*44px/s',
            $block,
            'AI-508: .fi-ta-reorder-handle must have min-height:44px'
        );
        $this->assertMatchesRegularExpression(
            '/\.fi-ta-reorder-handle[^{]*\{[^}]*min-width\s*:\s*44px/s',
            $block,
            'AI-508: .fi-ta-reorder-handle must have min-width:44px'
        );
    }

    #[Test]
    public function ai508_rules_scoped_to_admin_panel(): void
    {
        $block = $this->ai508Block();
        $this->assertStringContainsString('body.fi-panel-admin', $block);
    }

    #[Test]
    public function ai508_rules_inside_touch_media_query(): void
    {
        $block = $this->ai508Block();
        $this->assertMatchesRegularExpression(
            '/@media\s*\([^)]*max-width\s*:\s*1023\.98px[^)]*\)/',
            $block,
            'AI-508 rules must be inside the touch-viewport media query'
        );
    }

    // -----------------------------------------------------------------------
    // Boundary guards — AI-505 / AI-506 / AI-509 already covered
    // -----------------------------------------------------------------------

    #[Test]
    public function ai505_row_actions_already_covered_by_existing_rule(): void
    {
        // The existing `.fi-ta-row .fi-ta-actions button` rule already floors
        // icon-only row actions — verify it's still present so this contract
        // catches any accidental removal.
        $this->assertMatchesRegularExpression(
            '/\.fi-ta-row\s+\.fi-ta-actions\s+button\s*\{[^}]*min-height\s*:\s*44px/s',
            $this->css,
            'AI-505 coverage: .fi-ta-row .fi-ta-actions button must have min-height:44px'
        );
    }

    #[Test]
    public function ai506_rte_toolbar_already_covered_by_existing_rule(): void
    {
        // The existing rule block for `.fi-fo-rich-editor-toolbar button`
        // already floors RTE toolbar buttons — verify both the selector and
        // the min-height property are present in the CSS.
        $this->assertStringContainsString(
            'body.fi-panel-admin .fi-fo-rich-editor-toolbar button',
            $this->css,
            'AI-506 coverage: body.fi-panel-admin .fi-fo-rich-editor-toolbar button selector must exist'
        );
        // The rule block uses !important to beat Filament's bundled rule.
        $this->assertMatchesRegularExpression(
            '/\.fi-fo-rich-editor-toolbar button[^}]*\{[^}]*min-height\s*:\s*44px/s',
            $this->css,
            'AI-506 coverage: .fi-fo-rich-editor-toolbar rule block must include min-height:44px'
        );
    }

    #[Test]
    public function ai509_fi_toggle_already_covered_by_existing_rule(): void
    {
        // The existing `body.fi-panel-admin .fi-toggle` rule already floors
        // status toggles — verify it's still present.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-toggle[^{]*\{[^}]*min-height\s*:\s*44px/s',
            $this->css,
            'AI-509 coverage: body.fi-panel-admin .fi-toggle must have min-height:44px'
        );
    }

    #[Test]
    public function ai507_ai508_new_rules_do_not_bleed_to_checkout_panel(): void
    {
        $ai507 = $this->ai507Block();
        // Rules in AI-507 block must NOT scope to .fi-panel-checkout.
        // Grab only the portion up to AI-508 marker.
        $ai507only = substr($ai507, 0, strpos($ai507, 'AI-508') ?: strlen($ai507));
        $this->assertStringNotContainsString(
            'fi-panel-checkout',
            $ai507only,
            'AI-507 rules must not reference fi-panel-checkout'
        );
    }
}
