<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1152 / task-2026-05-28-af5d5b Batch 1 #1.
 *
 * Scope: Filament modal close button 36×36px below WCAG 2.5.5 44px minimum.
 *
 * Status: fully covered by AI-1165 / task-2026-05-27-db429e (commit eca6660ebd)
 * which added min-width: 44px + min-height: 44px to .fi-modal-close-btn in
 * microweber-theme-v3.scss. This contract test pins the existing rule so
 * AI-1152 cannot regress.
 */
class Admin1152AI1152ModalCloseTouchTargetContractTest extends TestCase
{
    private string $themeScss;
    private string $builtCss;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $root = self::projectRoot();

        $this->themeScss = (string) file_get_contents(
            $root . '/packages/microweber-filament-theme/resources/assets/css/filament/support/modal/theme.css'
        );

        $this->builtCss = (string) file_get_contents(
            $root . '/public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        );
    }

    public function test_modal_close_btn_min_width_44px_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.fi-modal-close-btn\s*\{[^}]*min-width:\s*44px\s*!important/s',
            $this->themeScss,
            'AI-1152: .fi-modal-close-btn must have min-width: 44px !important (lineage AI-1165)'
        );
    }

    public function test_modal_close_btn_min_height_44px_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.fi-modal-close-btn\s*\{[^}]*min-height:\s*44px\s*!important/s',
            $this->themeScss,
            'AI-1152: .fi-modal-close-btn must have min-height: 44px !important (lineage AI-1165)'
        );
    }

    public function test_modal_close_btn_min_width_44px_in_built_bundle(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.fi-modal-close-btn[^{}]*\{[^}]*min-width:\s*44px/s',
            $this->builtCss,
            'AI-1152: built CSS bundle must contain .fi-modal-close-btn min-width: 44px'
        );
    }

    public function test_modal_close_btn_min_height_44px_in_built_bundle(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.fi-modal-close-btn[^{}]*\{[^}]*min-height:\s*44px/s',
            $this->builtCss,
            'AI-1152: built CSS bundle must contain .fi-modal-close-btn min-height: 44px'
        );
    }
}
