<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-67db1e / AI-883 HIGH — Shop module touch-target fixes.
 *
 * Tester measured 2 WCAG 2.5.5 violations at 390×844:
 *   1. Category filter button: 366×40px (height 40 < 44px floor)
 *   2. Per-page selector: 38×44px (width 38 < 44px floor)
 *
 * Investigate result: empty cart dropdown (280×18px) is .mw-big-dropdown-cart
 * before cart items load — not an interactive element, no touch-target fix needed.
 *
 * Fix: new @media (max-width: 1023.98px), (hover: none) and (pointer: coarse)
 * block in public-touch.css following AI-522..AI-535 touch-target pattern.
 */
class Shop67db1eAI883TouchTargetContractTest extends TestCase
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
        $this->srcStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->src) ?? $this->src;
        $this->served = (string) file_get_contents(
            base_path('public/templates/bootstrap/css/public-touch.css')
        );
    }

    // ─── Fix 1: category filter button min-height ────────────────────────────

    #[Test]
    public function category_filter_button_gets_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.mw-shop-filter-categories\s+\.list-group-item\.list-group-item-action[^{]*\{[^}]*min-height:\s*44px~s',
            $this->src,
            '.mw-shop-filter-categories .list-group-item.list-group-item-action must have min-height: 44px.'
        );
    }

    #[Test]
    public function category_filter_rule_inside_touch_media_query(): void
    {
        $markerPos = strrpos($this->srcStripped, '.mw-shop-filter-categories');
        $this->assertNotFalse($markerPos,
            '.mw-shop-filter-categories selector must exist in comment-stripped source.');

        $slice = substr($this->srcStripped, max(0, $markerPos - 300), 400);
        $this->assertStringContainsString('@media', $slice,
            'The category filter touch-target rule must be inside an @media block.');
    }

    // ─── Fix 2: per-page select min-width ────────────────────────────────────

    #[Test]
    public function per_page_select_gets_min_width_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~select\.form-control[^{]*\{[^}]*min-width:\s*44px~s',
            $this->src,
            'select.form-control must have min-width: 44px (per-page Limit selector).'
        );
    }

    #[Test]
    public function per_page_select_rule_inside_touch_media_query(): void
    {
        $markerPos = strrpos($this->srcStripped, 'select.form-control');
        $this->assertNotFalse($markerPos, 'select.form-control selector must exist in stripped source.');

        $slice = substr($this->srcStripped, max(0, $markerPos - 300), 400);
        $this->assertStringContainsString('@media', $slice,
            'The per-page selector touch-target rule must be inside an @media block.');
    }

    // ─── Touch @media block uses WCAG-standard viewport query ────────────────

    #[Test]
    public function ai883_block_uses_standard_touch_media_query(): void
    {
        $markerPos = strrpos($this->src, 'task-2026-05-22-67db1e');
        $this->assertNotFalse($markerPos, 'task-2026-05-22-67db1e marker must exist.');

        $slice = substr($this->src, $markerPos, 1500);
        $this->assertStringContainsString(
            '@media (max-width: 1023.98px), (hover: none) and (pointer: coarse)',
            $slice,
            'AI-883 touch-target block must use the standard WCAG touch-viewport @media query.'
        );
    }

    // ─── Investigate result: cart dropdown documented, no fix needed ──────────

    #[Test]
    public function cart_dropdown_investigation_documented(): void
    {
        // The 280x18px cart dropdown is the .mw-big-dropdown-cart empty-state —
        // not an interactive element. The investigation result is documented in
        // the CSS comment block. Assert the marker exists as audit evidence.
        $this->assertStringContainsString(
            'task-2026-05-22-67db1e',
            $this->src,
            'task-2026-05-22-67db1e marker must be present documenting the investigation result.'
        );
    }

    // ─── Regression guards ────────────────────────────────────────────────────

    #[Test]
    public function ai877_bare_link_color_rule_still_present(): void
    {
        $this->assertStringContainsString(
            'a:not(.btn):not(.navbar-brand):not([class*="btn-"])',
            $this->srcStripped,
            'AI-877 bare-link color override must still be present.'
        );
    }

    #[Test]
    public function ai900_btn_sm_rule_still_present(): void
    {
        $this->assertStringContainsString(
            'button.btn.btn-sm:not(.btn-outline-primary)',
            $this->srcStripped,
            'AI-900 .btn-sm salmon fix must still be present.'
        );
    }

    // ─── Source + served mirror parity ───────────────────────────────────────

    #[Test]
    public function source_and_served_mirror_are_byte_identical(): void
    {
        $this->assertSame(
            $this->src,
            $this->served,
            'Templates/Bootstrap/.../public-touch.css and public/.../public-touch.css must be byte-identical.'
        );
    }
}
