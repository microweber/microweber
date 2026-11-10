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
    public function live_edit_cta_moves_to_the_sidebar_top(): void
    {
        $src = $this->read('packages/microweber-admin/src/Filament/FilamentAdminPanelProvider.php');
        // Registered at the top of the sidebar nav, wrapped for full-width.
        $this->assertStringContainsString('PanelsRenderHook::SIDEBAR_NAV_START', $src);
        $this->assertStringContainsString('mw-admin-sidebar-live-edit', $src);
        $this->assertStringContainsString('top-navigation-go-live-edit', $src);

        // The GLOBAL_SEARCH_AFTER topbar cluster no longer renders the Live Edit
        // view — only the quick-nav search remains there. Assert the go-live-edit
        // view is NOT rendered in the same expression as search-quick-nav.
        $this->assertMatchesRegularExpression(
            '/GLOBAL_SEARCH_AFTER,\s*hook:\s*fn\(\):\s*string\s*=>\s*view\(\'admin::livewire\.filament\.search-quick-nav\'\)->render\(\)\s*\);/s',
            $src,
            'GLOBAL_SEARCH_AFTER must render only the quick-nav search (Live Edit moved to the sidebar).'
        );
    }

    #[Test]
    public function sidebar_live_edit_cta_has_full_width_styling(): void
    {
        // The theme CSS is authored in per-concern parts (built into the bundle by
        // build.cjs; the built bundle is gitignored, so we assert the source part).
        $css = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/general/11-topbar-and-dashboard-1-topbar.css');
        $this->assertStringContainsString('.mw-admin-sidebar-live-edit', $css);
        $this->assertMatchesRegularExpression(
            '/\.mw-admin-sidebar-live-edit\s+\.fi-btn\s*\{[^}]*width:\s*100%/s',
            $css,
            'The sidebar Live Edit CTA must be full-width.'
        );
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
