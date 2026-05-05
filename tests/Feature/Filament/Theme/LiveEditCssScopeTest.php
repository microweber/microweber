<?php

namespace Tests\Feature\Filament\Theme;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LiveEditCssScopeTest extends TestCase
{
    #[Test]
    public function live_edit_filament_tab_overrides_are_scoped_to_the_live_edit_wrapper(): void
    {
        $path = base_path('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-action-links.css');

        $this->assertFileExists($path);

        $content = file_get_contents($path);

        $this->assertStringContainsString('.mw-admin-live-edit-page .fi-tabs .fi-tabs-item', $content);
        $this->assertStringNotContainsString("\n.fi-tabs .fi-tabs-item,\n.fi-tabs .fi-tabs-item .fi-tabs-item-label", $content);
    }

    #[Test]
    public function live_edit_filament_input_overrides_are_scoped_to_the_live_edit_wrapper(): void
    {
        $path = base_path('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-input.css');

        $this->assertFileExists($path);

        $content = file_get_contents($path);

        $this->assertStringContainsString('.mw-admin-live-edit-page .fi-modal-window .form-control-live-edit-label-wrapper .form-control-live-edit-input', $content);
        $this->assertStringNotContainsString("\n.fi-modal-window .form-control-live-edit-label-wrapper .form-control-live-edit-input {", $content);
    }

    #[Test]
    public function live_edit_modal_overlay_overrides_are_not_global(): void
    {
        $generalStyles = file_get_contents(base_path('packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'));
        $liveEditClasses = file_get_contents(base_path('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css'));

        $this->assertStringContainsString('.mw-admin-live-edit-page .fi-modal-close-overlay', $generalStyles);
        $this->assertStringNotContainsString("\n.fi-modal-close-overlay {", $generalStyles);

        $this->assertStringContainsString('.mw-admin-live-edit-page .fi-modal > .fi-modal-close-overlay', $liveEditClasses);
        $this->assertStringContainsString('.mw-admin-live-edit-page .fi-modal > .fi-modal-window-ctn', $liveEditClasses);
    }
}
