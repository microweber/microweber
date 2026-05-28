<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1218 / task-2026-05-28-102772.
 *
 * P2: Add Content modal had two issues:
 * 1. aria-labelledby on .fi-modal pointed to non-existent heading ID
 *    — Filament emits aria-labelledby="{id}.heading" but the h2 has
 *    no id attribute. Fix: x-init patches the heading id at mount.
 * 2. Cancel button measured 76x42px on mobile — 2px below WCAG 2.5.5
 *    44px minimum. Fix: min-height: 44px on .fi-modal-footer-actions
 *    .fi-btn within the picker modal scope.
 */
class LiveEdit102772AI1218AriaLabelledbyTouchTargetContractTest extends TestCase
{
    private string $addContentBlade;
    private string $liveEditCss;

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

        $this->liveEditCss = (string) file_get_contents(
            $root . '/packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css'
        );
    }

    public function test_xinit_patches_heading_id_from_aria_labelledby(): void
    {
        $this->assertStringContainsString(
            'aria-labelledby',
            $this->addContentBlade,
            'AI-1218: x-init must reference aria-labelledby to patch heading id'
        );

        $this->assertMatchesRegularExpression(
            '/closest.*role.*dialog/s',
            $this->addContentBlade,
            'AI-1218: x-init must find the parent dialog element'
        );

        $this->assertMatchesRegularExpression(
            '/fi-modal-heading/s',
            $this->addContentBlade,
            'AI-1218: x-init must target .fi-modal-heading to set its id'
        );
    }

    public function test_heading_id_assignment_logic(): void
    {
        $this->assertMatchesRegularExpression(
            '/h\.id\s*=\s*lbl/',
            $this->addContentBlade,
            'AI-1218: must assign aria-labelledby value as the heading id'
        );
    }

    public function test_cancel_button_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-content-picker-modal\s+\.fi-modal-footer-actions\s+\.fi-btn\s*\{[^}]*min-height:\s*44px\s*!important/s',
            $this->liveEditCss,
            'AI-1218: Cancel button must have min-height: 44px !important'
        );
    }

    public function test_cancel_button_rule_scoped_to_picker_modal(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-content-picker-modal\s+\.fi-modal-footer-actions/s',
            $this->liveEditCss,
            'AI-1218: Cancel button rule must be scoped to .mw-content-picker-modal'
        );
    }

    public function test_modal_heading_text_exists_in_action(): void
    {
        $adminPage = (string) file_get_contents(
            self::projectRoot() . '/src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php'
        );

        $this->assertStringContainsString(
            "->modalHeading('Add new content')",
            $adminPage,
            'AI-1218: addContentAction must have modalHeading set'
        );
    }

    public function test_modal_cancel_label_exists(): void
    {
        $adminPage = (string) file_get_contents(
            self::projectRoot() . '/src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php'
        );

        $this->assertStringContainsString(
            "->modalCancelActionLabel('Cancel')",
            $adminPage,
            'AI-1218: addContentAction must have Cancel button label'
        );
    }

    public function test_built_css_contains_cancel_button_rule(): void
    {
        $builtCss = (string) file_get_contents(
            self::projectRoot() . '/packages/microweber-filament-theme/resources/dist/build/microweber-filament-theme.css'
        );

        $this->assertStringContainsString(
            '.mw-content-picker-modal .fi-modal-footer-actions .fi-btn',
            $builtCss,
            'AI-1218: built CSS must contain the Cancel button min-height rule'
        );
    }
}
