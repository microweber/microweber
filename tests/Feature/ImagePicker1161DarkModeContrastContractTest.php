<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1161 / task-2026-05-28-af5d5b Batch 1 #9.
 *
 * Scope (dispatch verbatim): "Image Picker filepond drop label 1.8:1
 * contrast in dark mode. Fix: increase contrast to ≥4.5:1."
 *
 * Implementation:
 *   - html.dark scoped rule on .filepond--drop-label + nested label +
 *     .filepond--label-action sets color: rgb(229, 231, 235) (Tailwind
 *     gray-200), which on Filament's dark-mode body background
 *     (gray-950 / rgb(9, 9, 11)) yields ~17:1 contrast — well above
 *     the WCAG AA 4.5:1 threshold for non-large text.
 *   - !important required to defeat filepond's library defaults which
 *     win source-order against unscoped overrides.
 *
 * This contract pins both the source rule (microweber-theme-v3.scss tail)
 * and the built bundle (Webpack microweber-filament-theme.css).
 */
class ImagePicker1161DarkModeContrastContractTest extends TestCase
{
    private string $themeScss;
    private string $builtThemeCss;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $root = self::projectRoot();

        $this->themeScss = (string) file_get_contents(
            $root . '/packages/microweber-filament-theme/resources/assets/css/microweber-theme-v3.scss'
        );

        $this->builtThemeCss = (string) file_get_contents(
            $root . '/packages/microweber-filament-theme/resources/dist/build/microweber-filament-theme.css'
        );
    }

    // --- Source: dark-mode rule + !important + high-contrast color ---

    public function test_dark_drop_label_rule_present_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/html\.dark\s+\.filepond--drop-label[^{]*\{[^}]*color:\s*rgb\(229,\s*231,\s*235\)\s*!important/s',
            $this->themeScss,
            'AI-1161: html.dark .filepond--drop-label must set color: rgb(229, 231, 235) !important'
        );
    }

    public function test_dark_label_action_rule_present_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/html\.dark\s+\.filepond--label-action/',
            $this->themeScss,
            'AI-1161: html.dark .filepond--label-action must be covered by the contrast fix'
        );
    }

    public function test_dark_nested_label_covered_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/html\.dark\s+\.filepond--drop-label\s+label/',
            $this->themeScss,
            'AI-1161: html.dark .filepond--drop-label label (nested label element) must be covered'
        );
    }

    public function test_ai_1161_task_id_marker_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-af5d5b / AI-1161',
            $this->themeScss,
            'AI-1161: task-id marker comment must be present adjacent to the fix'
        );
    }

    // --- Built bundle: cross-pipeline regression guard ---

    public function test_dark_drop_label_rule_present_in_built_bundle(): void
    {
        $this->assertMatchesRegularExpression(
            '/html\.dark\s+\.filepond--drop-label[^{]*\{[^}]*color:\s*rgb\(229,\s*231,\s*235\)\s*!important/s',
            $this->builtThemeCss,
            'AI-1161: built theme bundle must contain the html.dark filepond drop label contrast rule'
        );
    }

    public function test_dark_label_action_text_decoration_in_built_bundle(): void
    {
        $this->assertMatchesRegularExpression(
            '/html\.dark\s+\.filepond--label-action\s*\{[^}]*text-decoration-color:\s*rgb\(229,\s*231,\s*235\)\s*!important/s',
            $this->builtThemeCss,
            'AI-1161: built bundle must include text-decoration-color override so the underline also meets contrast'
        );
    }
}
