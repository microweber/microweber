<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-903d56 / AI-903 — Advanced button: floating popover → right-rail side panel
 *
 * Problem: "More" button opened a floating center popover while all other 4 right-rail
 * buttons (Layout, Template, Style, AI Edit) open right-rail side panels. Largest
 * consistency gap in the live-edit sidebar.
 *
 * Fix: Convert to controlBox right-rail panel using the same pattern as templateSettingsWidget.
 * Panel uses Teleport to mount ToolsButtons (template="menu") inside the controlBox DOM.
 * Title: "Advanced". All 7 items remain functional after conversion.
 *
 * Nav arrows (→) added to non-destructive items via CSS.
 * Destructive items (Reset Content, Clear Cache) styled with --ese-danger text.
 * Separator before destructive group via :has() CSS.
 *
 * Files:
 *  - packages/frontend-assets/resources/assets/ui/components/Toolbar/SettingsCustomize.vue
 *  - packages/frontend-assets/resources/assets/ui/components/RightSidebar/ToolsButtons.vue
 *  - packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css
 */
class SettingsCustomize903d56AI903AdvancedPanelContractTest extends TestCase
{
    private string $settingsVue;
    private string $settingsStripped;
    private string $toolsVue;
    private string $toolsStripped;
    private string $leCss;
    private string $leCssStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $rawSettings = (string) file_get_contents(
            base_path('packages/frontend-assets/resources/assets/ui/components/Toolbar/SettingsCustomize.vue')
        );
        $this->settingsVue = $rawSettings;
        $stripped = preg_replace('~<!--[\s\S]*?-->~s', '', $rawSettings) ?? $rawSettings;
        $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $stripped) ?? $stripped;
        $this->settingsStripped = preg_replace('~//[^\n]*~', '', $stripped) ?? $stripped;

        $rawTools = (string) file_get_contents(
            base_path('packages/frontend-assets/resources/assets/ui/components/RightSidebar/ToolsButtons.vue')
        );
        $this->toolsVue = $rawTools;
        $this->toolsStripped = preg_replace('~<!--[\s\S]*?-->~s', '', $rawTools) ?? $rawTools;

        $rawCss = (string) file_get_contents(
            base_path('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css')
        );
        $this->leCss = $rawCss;
        $this->leCssStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $rawCss) ?? $rawCss;
    }

    // ─── Task marker ──────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present_in_files(): void
    {
        $this->assertStringContainsString('task-2026-05-22-903d56', $this->settingsVue,
            'SettingsCustomize.vue must carry the AI-903 task marker.'
        );
        $this->assertStringContainsString('task-2026-05-22-903d56', $this->leCss,
            'live-edit-classes.css must carry the AI-903 task marker.'
        );
    }

    // ─── Structural: popup removed, Teleport added ────────────────────────────

    #[Test]
    public function floating_popup_div_is_removed(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '~class="advanced-popup"~',
            $this->settingsStripped,
            'The .advanced-popup floating div must be removed (replaced by controlBox panel).'
        );
    }

    #[Test]
    public function teleport_mounts_into_advanced_panel_content(): void
    {
        $this->assertStringContainsString('Teleport', $this->settingsStripped,
            'SettingsCustomize.vue must use <Teleport> to mount ToolsButtons into the panel.'
        );
        $this->assertStringContainsString('#mw-advanced-panel-content', $this->settingsStripped,
            'Teleport target must be #mw-advanced-panel-content inside the controlBox.'
        );
    }

    #[Test]
    public function teleport_gated_by_advanced_panel_box_created(): void
    {
        $this->assertStringContainsString('advancedPanelBoxCreated', $this->settingsStripped,
            'Teleport must be gated on advancedPanelBoxCreated so it activates only after controlBox DOM exists.'
        );
    }

    // ─── controlBox pattern ───────────────────────────────────────────────────

    #[Test]
    public function control_box_created_on_mount(): void
    {
        $this->assertStringContainsString('advancedPanelWidget', $this->settingsStripped,
            'mw.app.advancedPanelWidget controlBox must be created in mounted().'
        );
        $this->assertStringContainsString('mw-live-edit-advanced-panel-box', $this->settingsStripped,
            'controlBox must use id mw-live-edit-advanced-panel-box for CSS scoping.'
        );
        $this->assertStringContainsString("mw-advanced-panel-content", $this->settingsStripped,
            'controlBox content div must have id mw-advanced-panel-content as the Teleport target.'
        );
    }

    #[Test]
    public function panel_title_is_advanced(): void
    {
        // The controlBox title should be "Advanced" (matching the button tooltip)
        $this->assertMatchesRegularExpression(
            "~mw\.lang\('Advanced'\)~",
            $this->settingsStripped,
            'Panel title must be "Advanced" via mw.lang().'
        );
    }

    #[Test]
    public function panel_position_is_right(): void
    {
        // strrpos: the LAST occurrence of 'advancedPanelWidget' is in the controlBox creation block
        $pos = strrpos($this->settingsStripped, "position: 'right'");
        $this->assertNotFalse($pos);
        // Verify it's in the context of the advancedPanelWidget creation (search backward for it)
        $before = substr($this->settingsStripped, 0, (int) $pos);
        $widgetPos = strrpos($before, 'advancedPanelWidget');
        $this->assertNotFalse($widgetPos,
            'Advanced panel controlBox must use position:right (same as other 4 right-rail panels).'
        );
    }

    #[Test]
    public function button_active_class_uses_button_is_active_advanced(): void
    {
        $this->assertStringContainsString('buttonIsActiveAdvanced', $this->settingsStripped,
            'Advanced button :class must use buttonIsActiveAdvanced (not the old advanced boolean).'
        );
    }

    #[Test]
    public function button_is_active_advanced_tracked_via_controlbox_events(): void
    {
        // Wire up show/hide events from the controlBox
        $this->assertMatchesRegularExpression(
            '~advancedPanelWidget\)?\s*\.on\s*\(\s*[\'"]show[\'"]~s',
            $this->settingsStripped,
            'buttonIsActiveAdvanced must be set to true via controlBox show event.'
        );
        $this->assertMatchesRegularExpression(
            '~advancedPanelWidget\)?\s*\.on\s*\(\s*[\'"]hide[\'"]~s',
            $this->settingsStripped,
            'buttonIsActiveAdvanced must be set to false via controlBox hide event.'
        );
    }

    #[Test]
    public function handle_advanced_calls_toggle_on_widget(): void
    {
        $this->assertMatchesRegularExpression(
            '~advancedPanelWidget\??\s*\.\s*toggle\s*\(\s*\)~',
            $this->settingsStripped,
            'handleAdvanced() must call mw.app.advancedPanelWidget?.toggle().'
        );
    }

    // ─── Old popup CSS removed ─────────────────────────────────────────────────

    #[Test]
    public function popup_animation_css_removed(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '~\.advanced-enter-active~',
            $this->settingsStripped,
            'Popup animation .advanced-enter-active must be removed from SettingsCustomize.vue CSS.'
        );
    }

    #[Test]
    public function popup_layout_css_removed(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '~\.advanced-popup\s*\{~',
            $this->settingsStripped,
            '.advanced-popup {} CSS must be removed (panel uses controlBox instead).'
        );
    }

    // ─── Regression: all 6 items remain functional ────────────────────────────

    #[Test]
    public function tools_buttons_still_in_teleport_template(): void
    {
        $this->assertStringContainsString('ToolsButtons', $this->settingsStripped,
            'ToolsButtons must still be rendered (inside the Teleport) for the panel content.'
        );
        $this->assertStringContainsString('template="menu"', $this->settingsStripped,
            'ToolsButtons must use template="menu" to show text labels in the panel.'
        );
    }

    #[Test]
    public function setup_wizard_item_present_in_tools_buttons(): void
    {
        $this->assertStringContainsString('openSetupWizard', $this->toolsStripped,
            'Setup wizard button must still be present in ToolsButtons.vue.'
        );
    }

    #[Test]
    public function code_editor_item_present_in_tools_buttons(): void
    {
        $this->assertStringContainsString('showCodeEditor', $this->toolsStripped,
            'Code Editor button must still be present in ToolsButtons.vue.'
        );
    }

    #[Test]
    public function reset_content_is_marked_destructive(): void
    {
        // The destructive class appears on the <button> element BEFORE the v-on:click attr in HTML order
        $this->assertMatchesRegularExpression(
            '~mw-live-edit-advanced-settings-popup--destructive[\s\S]{0,300}openContentResetContent~s',
            $this->toolsVue,
            'Reset Content button must have --destructive modifier class.'
        );
    }

    #[Test]
    public function clear_cache_is_marked_destructive(): void
    {
        $this->assertMatchesRegularExpression(
            '~clearCache\(\)[\s\S]{0,300}mw-live-edit-advanced-settings-popup--destructive|mw-live-edit-advanced-settings-popup--destructive[\s\S]{0,300}clearCache\(\)~s',
            $this->toolsVue,
            'Clear Cache button must have --destructive modifier class.'
        );
    }

    // ─── CSS: nav arrows + danger text ─────────────────────────────────────────

    #[Test]
    public function non_destructive_items_get_nav_arrow(): void
    {
        $this->assertMatchesRegularExpression(
            '~#mw-live-edit-advanced-panel-box[^{]*:not\(.mw-live-edit-advanced-settings-popup--destructive\)::after[^{]*\{[^}]*content:\s*[\'"]→[\'"]~s',
            $this->leCssStripped,
            'Non-destructive panel items must have a → nav arrow via CSS ::after.'
        );
    }

    #[Test]
    public function destructive_items_use_danger_color(): void
    {
        $this->assertMatchesRegularExpression(
            '~#mw-live-edit-advanced-panel-box[^{]*--destructive\s*\{[^}]*color:\s*var\(--ese-danger~s',
            $this->leCssStripped,
            'Destructive items must use --ese-danger color token.'
        );
    }

    #[Test]
    public function dark_mode_danger_color_present(): void
    {
        $this->assertStringContainsString('html.dark #mw-live-edit-advanced-panel-box', $this->leCssStripped,
            'Dark mode override for danger color must exist for the panel.'
        );
    }
}
