<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-05-mnu-modal — Menu "Add menu item" / Edit create-modal chrome.
 *
 * The MenusList Livewire component (rendered both on the standalone
 * /admin/menu-module-settings page and inside the live-edit Module Settings
 * iframe) renders its create/edit dialog via <x-filament-actions::modals/>.
 * A runtime probe found that dialog opened WITHOUT Filament modal overlay
 * chrome on this page: the close overlay computed to a fully transparent
 * background (no backdrop dim) and the window container aligned to the top of
 * the viewport instead of the centre, so the dialog read as a bare inline box
 * overlapping the menu list rather than a proper centred modal (the
 * "Add menu item does nothing / looks broken" report).
 *
 * The view restores both pieces, scoped to non-slide-over admin modals so the
 * live-edit slide-over panel keeps its own right-edge alignment.
 *
 * Verified in-browser: with the rule present the dialog renders centred with a
 * rgba(0,0,0,0.5) backdrop on BOTH surfaces, and a menu item submits + persists.
 *
 * Source: Modules/Menu/resources/views/livewire/admin/menus-list.blade.php
 */
class MenuMnumodalCreateModalChromeContractTest extends TestCase
{
    private string $blade;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(base_path(
            'Modules/Menu/resources/views/livewire/admin/menus-list.blade.php'
        ));
    }

    #[Test]
    public function backdrop_dim_restored_for_create_modal(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.fi-modal:not\(\.fi-modal-slide-over\)\s+\.fi-modal-close-overlay\s*\{[^}]*background-color:\s*rgba\(\s*0\s*,\s*0\s*,\s*0\s*,\s*0\.5\s*\)/s',
            $this->blade,
            'MenusList view must give the non-slide-over modal close overlay a dimmed backdrop.'
        );
    }

    #[Test]
    public function vertical_centering_restored_for_create_modal(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.fi-modal:not\(\.fi-modal-slide-over\)\s+\.fi-modal-window-ctn\s*\{[^}]*align-items:\s*center/s',
            $this->blade,
            'MenusList view must vertically centre the non-slide-over modal window container.'
        );
    }

    #[Test]
    public function rules_are_scoped_to_admin_panel_and_exclude_slide_over(): void
    {
        // Scoped under body.fi-panel-admin so the rule does not leak to other
        // panels, and :not(.fi-modal-slide-over) so the live-edit slide-over
        // panel keeps its own alignment.
        $this->assertStringContainsString('body.fi-panel-admin .fi-modal:not(.fi-modal-slide-over)', $this->blade,
            'The modal-chrome rules must be scoped to admin non-slide-over modals.');
    }

    #[Test]
    public function modals_portal_still_present(): void
    {
        $this->assertStringContainsString('<x-filament-actions::modals', $this->blade,
            'The MenusList view must still render the filament-actions modals portal it styles.');
    }
}
