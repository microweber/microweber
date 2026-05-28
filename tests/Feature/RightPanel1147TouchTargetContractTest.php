<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1147 / task-2026-05-28-af5d5b Batch 1 #6.
 *
 * Scope (dispatch verbatim): "Right-panel touch-target sweep: ESE close
 * button + icon strip below 44px. Fix: min-height/min-width 44px."
 *
 * Status: covered by two prior ships.
 *   - ESE close button (.x-close-modal-link): rule in
 *     packages/microweber-filament-theme/resources/assets/css/microweber/
 *     general-styles.css scopes 44x44 + inline-flex centering inside the
 *     .mw-live-edit-page wrapper so other modals do not inherit.
 *   - Right-rail icon strip (.live-edit-toolbar-buttons +
 *     .mw-live-edit-advanced-settings-popup): rule inside
 *     packages/frontend-assets/resources/assets/ui/components/Toolbar/
 *     SettingsCustomize.vue scopes 44x44 + display:flex centering.
 *
 * This contract test pins both invariants + the cross-pipeline coverage
 * (Vite frontend-assets surface + Webpack filament-theme surface).
 */
class RightPanel1147TouchTargetContractTest extends TestCase
{
    private string $generalStylesCss;
    private string $settingsCustomizeVue;
    private string $builtThemeCss;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $root = self::projectRoot();

        $this->generalStylesCss = (string) file_get_contents(
            $root . '/packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        );

        $this->settingsCustomizeVue = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/ui/components/Toolbar/SettingsCustomize.vue'
        );

        $this->builtThemeCss = (string) file_get_contents(
            $root . '/public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        );
    }

    // --- ESE close button (.x-close-modal-link) ---

    public function test_ese_close_button_min_height_44px_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.x-close-modal-link\s*\{[^}]*min-height:\s*44px/',
            $this->generalStylesCss,
            'AI-1147: .mw-live-edit-page .x-close-modal-link must carry min-height: 44px'
        );
    }

    public function test_ese_close_button_min_width_44px_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.x-close-modal-link\s*\{[^}]*min-width:\s*44px/',
            $this->generalStylesCss,
            'AI-1147: .mw-live-edit-page .x-close-modal-link must carry min-width: 44px'
        );
    }

    public function test_ese_close_button_centered_inline_flex_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.x-close-modal-link\s*\{[^}]*display:\s*inline-flex[^}]*align-items:\s*center[^}]*justify-content:\s*center/s',
            $this->generalStylesCss,
            'AI-1147: ESE close button must center SVG via inline-flex + align-items + justify-content'
        );
    }

    public function test_ese_close_button_rule_present_in_built_bundle(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.x-close-modal-link\s*\{[^}]*min-height:\s*44px/',
            $this->builtThemeCss,
            'AI-1147: built theme bundle must contain ESE close button 44x44 rule'
        );
    }

    // --- Right-rail icon strip (.live-edit-toolbar-buttons + .mw-live-edit-advanced-settings-popup) ---

    public function test_right_rail_icons_min_height_44px_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-advanced-settings-popup,\s*\.mw-live-edit-right-sidebar-wrapper\.mw-live-edit-right-sidebar-template-sidebar\s+\.btn-icon\.live-edit-toolbar-buttons\s*\{[^}]*min-height:\s*44px[^}]*!important/s',
            $this->settingsCustomizeVue,
            'AI-1147: right-rail icon buttons must carry min-height: 44px !important'
        );
    }

    public function test_right_rail_icons_dimensions_44px_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-advanced-settings-popup,\s*\.mw-live-edit-right-sidebar-wrapper\.mw-live-edit-right-sidebar-template-sidebar\s+\.btn-icon\.live-edit-toolbar-buttons\s*\{[^}]*height:\s*44px[^}]*!important[^}]*width:\s*44px[^}]*!important/s',
            $this->settingsCustomizeVue,
            'AI-1147: right-rail icon buttons must carry height + width 44px !important'
        );
    }

    public function test_right_rail_icons_centered_flex_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-advanced-settings-popup,\s*\.mw-live-edit-right-sidebar-wrapper\.mw-live-edit-right-sidebar-template-sidebar\s+\.btn-icon\.live-edit-toolbar-buttons\s*\{[^}]*display:\s*flex[^}]*align-items:\s*center[^}]*justify-content:\s*center/s',
            $this->settingsCustomizeVue,
            'AI-1147: right-rail icon buttons must center SVG via display:flex + align-items + justify-content'
        );
    }
}
