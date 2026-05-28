<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1217 / task-2026-05-28-d8b915.
 *
 * P1: Add Content modal dark-mode text invisible — description
 * paragraph and search input typed text used inherited dark text
 * color (#182433) against dark background (~#1b1b1b), giving
 * contrast ratio ~1.1:1 (WCAG AA requires 4.5:1).
 *
 * Fix: dark-mode CSS rules in live-edit-classes.css with
 * !important on .mw-add-content-modal-search-input (color: #e5e7eb)
 * and .mw-add-content-modal-root p (color: #d1d5db).
 */
class LiveEditD8b915AI1217DarkModeTextContrastContractTest extends TestCase
{
    private string $liveEditCss;
    private string $addContentBlade;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $root = self::projectRoot();

        $this->liveEditCss = (string) file_get_contents(
            $root . '/packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css'
        );

        $this->addContentBlade = (string) file_get_contents(
            $root . '/src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php'
        );
    }

    public function test_dark_search_input_text_color_important(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.dark\s+\.mw-add-content-modal-search-input\s*\{[^}]*color:\s*#e5e7eb\s*!important/s',
            $this->liveEditCss,
            'AI-1217: search input must have light text color with !important in dark mode'
        );
    }

    public function test_dark_description_paragraph_color_important(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.dark\s+\.mw-add-content-modal-root\s+p\s*\{[^}]*color:\s*#d1d5db\s*!important/s',
            $this->liveEditCss,
            'AI-1217: description paragraph must have light text color with !important in dark mode'
        );
    }

    public function test_search_input_has_class_in_blade(): void
    {
        $this->assertStringContainsString(
            'mw-add-content-modal-search-input',
            $this->addContentBlade,
            'AI-1217: search input must have the mw-add-content-modal-search-input class'
        );
    }

    public function test_modal_root_has_class_in_blade(): void
    {
        $this->assertStringContainsString(
            'mw-add-content-modal-root',
            $this->addContentBlade,
            'AI-1217: modal root must have the mw-add-content-modal-root class'
        );
    }

    public function test_description_paragraph_exists(): void
    {
        $this->assertStringContainsString(
            'Create something new for your website',
            $this->addContentBlade,
            'AI-1217: description paragraph must exist in the modal'
        );
    }

    public function test_built_css_contains_dark_search_rule(): void
    {
        $builtCss = (string) file_get_contents(
            self::projectRoot() . '/packages/microweber-filament-theme/resources/dist/build/microweber-filament-theme.css'
        );

        $this->assertStringContainsString(
            '.mw-add-content-modal-search-input',
            $builtCss,
            'AI-1217: built CSS must contain the dark search input text rule'
        );
    }

    public function test_built_css_contains_dark_paragraph_rule(): void
    {
        $builtCss = (string) file_get_contents(
            self::projectRoot() . '/packages/microweber-filament-theme/resources/dist/build/microweber-filament-theme.css'
        );

        $this->assertStringContainsString(
            '.mw-add-content-modal-root p',
            $builtCss,
            'AI-1217: built CSS must contain the dark paragraph text rule'
        );
    }

    public function test_search_input_dark_color_contrast_sufficient(): void
    {
        preg_match(
            '/\.dark\s+\.mw-add-content-modal-search-input\s*\{[^}]*color:\s*#([0-9a-fA-F]{6})\s*!important/s',
            $this->liveEditCss,
            $matches
        );

        $this->assertNotEmpty($matches, 'AI-1217: dark search input color rule must exist');

        $hex = $matches[1];
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $lum = 0.2126 * pow($r / 255, 2.2) + 0.7152 * pow($g / 255, 2.2) + 0.0722 * pow($b / 255, 2.2);
        $bgLum = 0.2126 * pow(0x1b / 255, 2.2) + 0.7152 * pow(0x1b / 255, 2.2) + 0.0722 * pow(0x1b / 255, 2.2);

        $contrast = ($lum + 0.05) / ($bgLum + 0.05);

        $this->assertGreaterThanOrEqual(
            4.5,
            $contrast,
            "AI-1217: search input text contrast ratio {$contrast}:1 must be >= 4.5:1 against dark bg"
        );
    }
}
