<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-62 / TICKET-U/V/W/X/Y/Z — mobile-audit follow-up regression
 * coverage. Pins the 44x44 touch-target sweep across:
 *
 *   U: admin topbar controls (sidebar toggle, search input, profile
 *      button, Live Edit chip)
 *   V: list-page row controls (checkboxes, action icons, badges,
 *      title link, pagination select)
 *   W: Orders status tabs (.fi-tabs-item)
 *   X: public-site primary nav links
 *   Y: public-site footer links
 *   Z: public-site "Go Live Edit" chip
 *
 * Source: agent-test mobile-audit brief 2026-05-08T18:32:56Z.
 *
 * Style after the cycle-52..61 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class MobileAuditFollowupContractTest extends TestCase
{
    private string $adminTouchCss;
    private string $publicTouchSrc;
    private string $publicTouchServed;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminTouchCss = file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css'
        ));
        $this->publicTouchSrc = file_get_contents(base_path(
            'Templates/Bootstrap/resources/assets/css/public-touch.css'
        ));
        $this->publicTouchServed = file_get_contents(base_path(
            'public/templates/bootstrap/css/public-touch.css'
        ));
    }

    #[Test]
    public function ticket_u_admin_topbar_controls_carry_44px_minimum(): void
    {
        $required = [
            '.fi-topbar-nav-button',          // sidebar toggle
            '.fi-topbar-item-btn',            // user profile (Filament v5 renamed -item-button -> -item-btn)
            '.fi-global-search-field input',  // quick-nav search
            '.fi-topbar a.mw-go-live-edit',   // Live Edit chip in topbar
        ];
        foreach ($required as $sel) {
            $this->assertStringContainsString(
                $sel,
                $this->adminTouchCss,
                "mobile-touch.css: TICKET-U selector '{$sel}' must be present"
            );
        }
    }

    #[Test]
    public function ticket_v_admin_list_row_controls_carry_44px_minimum(): void
    {
        $required = [
            '.fi-ta-row .fi-ta-actions .fi-icon-btn',  // row action icon
            '.fi-ta-row .fi-badge',                    // status badge
            '.fi-ta-row .fi-ta-text a',                // title link
            '.fi-pagination select',                   // pagination select
        ];
        foreach ($required as $sel) {
            $this->assertStringContainsString(
                $sel,
                $this->adminTouchCss,
                "mobile-touch.css: TICKET-V selector '{$sel}' must be present"
            );
        }

        // Checkbox wrapper rule must use either the sibling-selector
        // (Filament v5 default) OR the :has() shape (modern fallback).
        $this->assertMatchesRegularExpression(
            '/\\.fi-ta-row\\s+\\.fi-checkbox-input\\s*\\+\\s*label|\\.fi-ta-row\\s+label:has\\(/',
            $this->adminTouchCss,
            'mobile-touch.css: TICKET-V row-checkbox wrapper rule must be present'
        );
    }

    #[Test]
    public function ticket_w_orders_status_tabs_carry_44px_minimum(): void
    {
        // Scoped to list pages via .fi-resource-list-records-page so
        // other tab usages (e.g. inside edit forms) are unaffected.
        $this->assertMatchesRegularExpression(
            '/\\.fi-resource-list-records-page\\s+\\.fi-tabs-item\\s*\\{[^}]*min-height:\\s*44px/',
            $this->adminTouchCss,
            'mobile-touch.css: TICKET-W .fi-tabs-item must have min-height: 44px scoped to list pages'
        );
    }

    #[Test]
    public function ticket_x_public_nav_links_carry_44px_minimum(): void
    {
        // Public navbar selectors. Multiple variants to cover the
        // different theme nav markup shapes.
        $required = [
            '.mw-nav-list a',
            '.mw-main-nav a',
            'nav.navbar .navbar-nav .nav-link',
            'nav ul.menu li a',
            'nav ul.nav li a',
        ];
        foreach ($required as $sel) {
            $this->assertStringContainsString(
                $sel,
                $this->publicTouchSrc,
                "public-touch.css: TICKET-X selector '{$sel}' must be present"
            );
        }
    }

    #[Test]
    public function ticket_y_public_footer_links_carry_44px_minimum(): void
    {
        $this->assertStringContainsString(
            '.mw-footer a',
            $this->publicTouchSrc,
            'public-touch.css: TICKET-Y .mw-footer a must be present'
        );
        $this->assertStringContainsString(
            'footer.mw-footer a',
            $this->publicTouchSrc,
            'public-touch.css: TICKET-Y footer.mw-footer a must be present'
        );
    }

    #[Test]
    public function ticket_z_go_live_edit_chip_carries_44px_minimum(): void
    {
        $this->assertMatchesRegularExpression(
            '/\\.mw-go-live-edit\\s*\\{\\s*min-height:\\s*44px/',
            $this->publicTouchSrc,
            'public-touch.css: TICKET-Z .mw-go-live-edit must have min-height: 44px'
        );
    }

    #[Test]
    public function source_and_served_public_touch_remain_byte_identical(): void
    {
        // The cycle-58 contract pinned this; cycle-62 must not break it.
        $this->assertSame(
            $this->publicTouchSrc,
            $this->publicTouchServed,
            'public-touch.css source and served copies must stay byte-identical after the cycle-62 sweep'
        );
    }
}
