<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1154 / task-2026-05-28-af5d5b Batch 1 #2.
 *
 * Scope: Dialog aria-labeling missing on Add Content modal + Image Picker.
 * Fix: correct `aria-labelledby` + `role="dialog"`.
 *
 * Status: covered by two prior ships:
 *   - Add Content modal: AI-1218 / task-2026-05-28-102772 (commit cfa73774a5)
 *     patches the runtime to copy the dialog's aria-labelledby value onto
 *     the .fi-modal-heading id so screen readers can resolve it.
 *   - Image Picker (mw.Dialog wrapper): task-2026-05-05-54cda2 already
 *     sets role=dialog + aria-modal=true on .mw-dialog and wires
 *     aria-labelledby to the title id created in header().
 *
 * This contract test pins both source-level invariants as regression guard.
 */
class Dialog1154AI1154AriaLabelledByContractTest extends TestCase
{
    private string $addContentBlade;
    private string $mwDialogJs;
    private string $builtFrontendJs;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $root = self::projectRoot();

        $this->addContentBlade = (string) file_get_contents(
            $root . '/src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php'
        );

        $this->mwDialogJs = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/components/dialog.js'
        );

        $this->builtFrontendJs = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/dist/build/admin.js'
        );
    }

    // --- Add Content modal (AI-1218 patch) ---

    public function test_add_content_modal_runtime_patches_heading_id(): void
    {
        $this->assertStringContainsString(
            "dlg.getAttribute('aria-labelledby')",
            $this->addContentBlade,
            'AI-1154: Add Content modal must read parent dialog aria-labelledby (AI-1218 patch)'
        );

        $this->assertStringContainsString(
            "querySelector('.fi-modal-heading')",
            $this->addContentBlade,
            'AI-1154: Add Content modal must locate .fi-modal-heading to copy id'
        );

        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*lbl\s*&&\s*h\s*&&\s*!h\.id\s*\)\s*h\.id\s*=\s*lbl/',
            $this->addContentBlade,
            'AI-1154: Add Content modal must assign aria-labelledby value as heading id when missing'
        );
    }

    // --- mw.Dialog (Image Picker wrapper) ---

    public function test_mw_dialog_main_carries_role_dialog(): void
    {
        $this->assertStringContainsString(
            "this.dialogMain.setAttribute('role', 'dialog')",
            $this->mwDialogJs,
            'AI-1154: mw.Dialog .dialogMain must carry role=dialog'
        );
    }

    public function test_mw_dialog_main_carries_aria_modal(): void
    {
        $this->assertStringContainsString(
            "this.dialogMain.setAttribute('aria-modal', 'true')",
            $this->mwDialogJs,
            'AI-1154: mw.Dialog .dialogMain must carry aria-modal=true'
        );
    }

    public function test_mw_dialog_header_sets_aria_labelledby_to_title_id(): void
    {
        $this->assertMatchesRegularExpression(
            "/var\s+titleId\s*=\s*this\.id\s*\+\s*'-title'/",
            $this->mwDialogJs,
            'AI-1154: mw.Dialog header() must compute titleId from dialog id'
        );

        $this->assertStringContainsString(
            "this.dialogMain.setAttribute('aria-labelledby', titleId)",
            $this->mwDialogJs,
            'AI-1154: mw.Dialog header() must wire aria-labelledby to titleId'
        );
    }

    public function test_mw_dialog_headless_fallback_uses_aria_label(): void
    {
        $this->assertStringContainsString(
            "this.dialogMain.setAttribute('aria-label', 'Dialog')",
            $this->mwDialogJs,
            'AI-1154: mw.Dialog headless fallback must set aria-label=Dialog'
        );
    }

    // --- Built bundle verification ---

    public function test_built_bundle_carries_role_dialog(): void
    {
        $this->assertStringContainsString(
            'dialogMain.setAttribute("role","dialog")',
            $this->builtFrontendJs,
            'AI-1154: built admin.js bundle must contain dialogMain role=dialog assignment'
        );
    }

    public function test_built_bundle_carries_aria_modal(): void
    {
        $this->assertStringContainsString(
            'dialogMain.setAttribute("aria-modal","true")',
            $this->builtFrontendJs,
            'AI-1154: built admin.js bundle must contain dialogMain aria-modal=true assignment'
        );
    }
}
