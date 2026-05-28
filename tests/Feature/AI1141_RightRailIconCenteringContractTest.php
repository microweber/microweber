<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1141 / task-2026-05-28-c97cd6.
 *
 * P1 regression: AI Edit icon in the vertical right-rail sidebar was
 * clipped/misaligned because generic toolbar button rules from
 * live-edit-classes.css won the cascade over the sidebar-specific
 * rules in SettingsCustomize.vue (the generic rules had !important,
 * the sidebar rules did not).
 *
 * Fix: sidebar button rules now carry !important on height, width,
 * display, align-items, justify-content, and padding so the vertical
 * sidebar context wins over the horizontal toolbar context.
 */
class AI1141_RightRailIconCenteringContractTest extends TestCase
{
    private string $settingsVue;
    private string $liveEditCss;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $root = self::projectRoot();

        $this->settingsVue = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/ui/components/Toolbar/SettingsCustomize.vue'
        );

        $this->liveEditCss = (string) file_get_contents(
            $root . '/packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css'
        );
    }

    public function test_sidebar_button_height_44px_important(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-wrapper\.mw-live-edit-right-sidebar-template-sidebar[\s\S]*?\.btn-icon\.live-edit-toolbar-buttons\s*\{[^}]*height:\s*44px\s*!important/s',
            $this->settingsVue,
            'AI-1141: sidebar buttons must have height: 44px !important'
        );
    }

    public function test_sidebar_button_width_44px_important(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-wrapper\.mw-live-edit-right-sidebar-template-sidebar[\s\S]*?\.btn-icon\.live-edit-toolbar-buttons\s*\{[^}]*width:\s*44px\s*!important/s',
            $this->settingsVue,
            'AI-1141: sidebar buttons must have width: 44px !important'
        );
    }

    public function test_sidebar_button_display_flex_important(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-wrapper\.mw-live-edit-right-sidebar-template-sidebar[\s\S]*?\.btn-icon\.live-edit-toolbar-buttons\s*\{[^}]*display:\s*flex\s*!important/s',
            $this->settingsVue,
            'AI-1141: sidebar buttons must have display: flex !important for centering'
        );
    }

    public function test_sidebar_button_align_items_center_important(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-wrapper\.mw-live-edit-right-sidebar-template-sidebar[\s\S]*?\.btn-icon\.live-edit-toolbar-buttons\s*\{[^}]*align-items:\s*center\s*!important/s',
            $this->settingsVue,
            'AI-1141: sidebar buttons must have align-items: center !important'
        );
    }

    public function test_sidebar_button_justify_content_center_important(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-wrapper\.mw-live-edit-right-sidebar-template-sidebar[\s\S]*?\.btn-icon\.live-edit-toolbar-buttons\s*\{[^}]*justify-content:\s*center\s*!important/s',
            $this->settingsVue,
            'AI-1141: sidebar buttons must have justify-content: center !important'
        );
    }

    public function test_sidebar_button_padding_zero_important(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-wrapper\.mw-live-edit-right-sidebar-template-sidebar[\s\S]*?\.btn-icon\.live-edit-toolbar-buttons\s*\{[^}]*padding:\s*0\s*!important/s',
            $this->settingsVue,
            'AI-1141: sidebar buttons must have padding: 0 !important to override generic toolbar padding'
        );
    }

    public function test_sidebar_button_overflow_visible(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-wrapper\.mw-live-edit-right-sidebar-template-sidebar[\s\S]*?\.btn-icon\.live-edit-toolbar-buttons\s*\{[^}]*overflow:\s*visible\s*!important/s',
            $this->settingsVue,
            'AI-1141: sidebar buttons must have overflow: visible !important'
        );
    }

    public function test_sidebar_svg_has_explicit_height(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-wrapper\.mw-live-edit-right-sidebar-template-sidebar[\s\S]*?\.btn-icon\.live-edit-toolbar-buttons\s*\{[^}]*svg\s*\{[^}]*height:\s*22px/s',
            $this->settingsVue,
            'AI-1141: sidebar button SVGs must have explicit height: 22px'
        );
    }

    public function test_sidebar_width_accommodates_44px_buttons(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-wrapper\.mw-live-edit-right-sidebar-template-sidebar\s*\{[^}]*width:\s*(\d+)px/s',
            $this->settingsVue,
        );

        preg_match(
            '/\.mw-live-edit-right-sidebar-wrapper\.mw-live-edit-right-sidebar-template-sidebar\s*\{[^}]*width:\s*(\d+)px/s',
            $this->settingsVue,
            $matches
        );

        $width = (int) $matches[1];
        $this->assertGreaterThanOrEqual(
            50,
            $width,
            'AI-1141: sidebar width must be at least 50px to accommodate 44px buttons'
        );
    }

    public function test_sidebar_max_height_none_important(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-wrapper\.mw-live-edit-right-sidebar-template-sidebar\s*\{[^}]*max-height:\s*none\s*!important/s',
            $this->settingsVue,
            'AI-1141: sidebar wrapper must override max-height to none !important'
        );
    }

    public function test_generic_wrapper_has_max_height(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-wrapper\s*\{[^}]*max-height:\s*44px\s*!important/s',
            $this->liveEditCss,
            'AI-1141: generic wrapper max-height 44px must exist (the rule that the sidebar overrides)'
        );
    }

    public function test_ai_edit_button_has_proper_classes(): void
    {
        $this->assertStringContainsString(
            'class="btn-icon live-edit-toolbar-buttons mw-toolbar-icon-btn"',
            $this->settingsVue,
            'AI-1141: AI Edit button must have btn-icon + live-edit-toolbar-buttons classes'
        );
    }

    public function test_ai_edit_button_has_aria_label(): void
    {
        $this->assertStringContainsString(
            'aria-label="Quick AI edit"',
            $this->settingsVue,
            'AI-1141: AI Edit button must have aria-label'
        );
    }
}
