<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Contract for the 2026-11-10 admin-chrome relocations:
 *   1. Bookings nests under the Shop group's "More" hub (navigationParentItem).
 *   2. Subsites nests under the "Settings" item (navigationParentItem).
 *   3. The Live Edit CTA moves from the topbar (GLOBAL_SEARCH_AFTER) to the TOP
 *      of the sidebar (SIDEBAR_NAV_START), above Dashboard.
 *   4. The admin AI chat trigger moves from a floating bottom-right bubble to a
 *      topbar button (USER_MENU_BEFORE); the Live Edit top toolbar gains its own
 *      AI chat button.
 *
 * Mostly PRESENCE checks; the one absence check (the old fixed-bubble inline
 * style) pre-strips comments so it cannot self-match its own prose.
 */
class AdminChromeRelocationContractTest extends TestCase
{
    private function read(string $rel): string
    {
        return (string) file_get_contents(base_path($rel));
    }

    private function stripPhpComments(string $src): string
    {
        $src = preg_replace('#/\*.*?\*/#s', '', $src) ?? $src;
        $src = preg_replace('#(^|\s)//[^\n]*#', '$1', $src) ?? $src;

        return $src;
    }

    // ── 1 + 2: nav nesting ────────────────────────────────────────────────────

    #[Test]
    public function bookings_nests_under_the_more_hub(): void
    {
        $src = $this->read('Modules/Booking/Filament/Resources/BookingResource.php');
        $this->assertMatchesRegularExpression(
            '/\$navigationParentItem\s*=\s*\'More\'/',
            $src,
            'BookingResource must nest under the "More" item.'
        );
        // Still in the Shop group (parent + child must share a group).
        $this->assertStringContainsString("\$navigationGroup = 'Shop'", $src);
    }

    #[Test]
    public function subsites_nests_under_settings(): void
    {
        $src = $this->read('Modules/MultisiteManager/Filament/Admin/Pages/AdminTenantSitesPage.php');
        $this->assertMatchesRegularExpression(
            '/\$navigationParentItem\s*=\s*\'Settings\'/',
            $src,
            'AdminTenantSitesPage (Subsites) must nest under the "Settings" item.'
        );
        $this->assertStringContainsString("\$navigationGroup = 'Settings'", $src);
    }

    // ── 3: Live Edit CTA relocated to the sidebar top ─────────────────────────

    #[Test]
    public function live_edit_green_button_stays_in_the_topbar(): void
    {
        $src = $this->read('packages/microweber-admin/src/Filament/FilamentAdminPanelProvider.php');
        // The green Live Edit button is rendered in the topbar cluster alongside
        // the quick-nav search (its original home).
        $this->assertMatchesRegularExpression(
            '/GLOBAL_SEARCH_AFTER,\s*hook:\s*fn\(\):\s*string\s*=>.*top-navigation-go-live-edit.*search-quick-nav/s',
            $src,
            'The Live Edit button must render in the GLOBAL_SEARCH_AFTER topbar cluster.'
        );
    }

    #[Test]
    public function sidebar_gets_an_additional_native_live_edit_nav_item(): void
    {
        $src = $this->read('packages/microweber-admin/src/Filament/FilamentAdminPanelProvider.php');
        // An ADDITIONAL Live Edit entry, rendered as a NATIVE Filament
        // NavigationItem (eye icon) so it matches the other sidebar items exactly
        // — not a custom link, and not the green button.
        $this->assertMatchesRegularExpression(
            "/NavigationItem::make\('Live Edit'\).*?editmode=y.*?->icon\('heroicon-o-eye'\).*?->sort\(-?\d+\)/s",
            $src,
            'The sidebar must register a native Live Edit NavigationItem with an eye icon.'
        );
        // The old custom sidebar-link markup/hook is gone.
        $this->assertStringNotContainsString('mw-admin-sidebar-live-edit-link', $src);
    }

    // ── 4: AI chat trigger to the topbar + Live Edit toolbar ──────────────────

    #[Test]
    public function admin_ai_chat_trigger_is_a_topbar_button_not_a_floating_bubble(): void
    {
        $src = $this->read('packages/microweber-filament/src/Providers/MicroweberFilamentServiceProvider.php');
        // Rendered in the topbar next to the user menu.
        $this->assertStringContainsString('PanelsRenderHook::USER_MENU_BEFORE', $src);
        $this->assertStringContainsString('mw-admin-ai-chat-trigger', $src);
        $this->assertStringContainsString('mw-admin-ai-chat-topbar', $src);

        // The old fixed bottom-right bubble styling is gone.
        $stripped = $this->stripPhpComments($src);
        $this->assertStringNotContainsString('bottom:20px', $stripped, 'The floating bottom-right AI bubble must be removed.');
        $this->assertStringNotContainsString('position:fixed;right:20px', $stripped, 'The floating AI bubble positioning must be removed.');
    }

    #[Test]
    public function live_edit_top_toolbar_has_an_ai_chat_button(): void
    {
        $vue = $this->read('packages/frontend-assets/resources/assets/ui/components/Toolbar/Toolbar.vue');
        $this->assertStringContainsString('mw-live-edit-ai-chat-button', $vue, 'The Live Edit top toolbar must have an AI chat button.');
        $this->assertStringContainsString('openAiChat', $vue, 'The toolbar must define the openAiChat handler.');
        $this->assertStringContainsString("'mw:ai-chat:toggle'", $vue, 'The toolbar AI button must toggle the chat sidebar.');
    }
}
