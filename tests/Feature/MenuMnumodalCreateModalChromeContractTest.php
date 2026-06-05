<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-05-mnu-modal — Module-settings create/edit modal chrome.
 *
 * Every live-edit Module Settings page (Menu, and any other
 * LiveEditModuleSettings subclass) renders inside the shared layout
 * filament/components/layout/live-edit-module-settings.blade.php, which carries
 * a <style> block. The project filament theme forces the Filament modal close
 * overlay to a transparent background and leaves the window container
 * top-aligned on this surface, so a module's own create/edit dialog (rendered
 * via <x-filament-actions::modals/>, e.g. the Menu "Add menu item" dialog)
 * opened with NO backdrop dim and uncentred — a bare inline box over the
 * settings list (the "add menu item looks broken / does nothing" report).
 *
 * The shared layout previously patched only the Content modals
 * (.mw-content-form-modal). Generic module create/edit modals carry no marker
 * class, so a generic rule was added covering every non-slide-over modal on the
 * module-settings surface — a single systemic fix for ALL modules. Because the
 * style block only loads inside the module-settings layout it cannot reach
 * working full-page admin modals.
 *
 * Verified in-browser across multiple module settings: the dialog renders
 * centred with a rgba(0,0,0,0.5) backdrop and a menu item submits + persists.
 */
class MenuMnumodalCreateModalChromeContractTest extends TestCase
{
    private string $layout;

    protected function setUp(): void
    {
        parent::setUp();
        $this->layout = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit-module-settings.blade.php'
        ));
    }

    #[Test]
    public function generic_backdrop_dim_restored_for_non_slide_over_modals(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-modal:not\(\.fi-modal-slide-over\)\s+\.fi-modal-close-overlay\s*\{[^}]*background-color:\s*rgba\(\s*0\s*,\s*0\s*,\s*0\s*,\s*0\.5\s*\)\s*!important/s',
            $this->layout,
            'Shared module-settings layout must give every non-slide-over modal close overlay a dimmed backdrop.'
        );
    }

    #[Test]
    public function generic_vertical_centering_restored_for_non_slide_over_modals(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-modal:not\(\.fi-modal-slide-over\)\s+\.fi-modal-window-ctn\s*\{[^}]*align-items:\s*center\s*!important/s',
            $this->layout,
            'Shared module-settings layout must vertically centre every non-slide-over modal window container.'
        );
    }

    #[Test]
    public function rule_excludes_slide_over_so_settings_panel_keeps_its_alignment(): void
    {
        // The :not(.fi-modal-slide-over) guard keeps the Module Settings
        // slide-over panel itself aligned to the right edge.
        $this->assertStringContainsString(':not(.fi-modal-slide-over)', $this->layout,
            'The generic modal-chrome rules must exclude slide-over modals.');
    }

    #[Test]
    public function menu_view_no_longer_carries_its_own_duplicate_fix(): void
    {
        // The per-component fix was superseded by the systemic layout fix.
        $menu = (string) file_get_contents(base_path(
            'Modules/Menu/resources/views/livewire/admin/menus-list.blade.php'
        ));
        $this->assertStringNotContainsString('.fi-modal-window-ctn {', $menu,
            'Menu view must not duplicate the modal-chrome CSS now living in the shared layout.');
        // ...but it still renders the modals portal that the layout styles.
        $this->assertStringContainsString('<x-filament-actions::modals', $menu,
            'Menu view must still render the filament-actions modals portal.');
    }
}
