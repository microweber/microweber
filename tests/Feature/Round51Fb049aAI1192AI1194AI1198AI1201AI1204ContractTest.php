<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for Round 51 5xP3 batch (task-2026-05-27-fb049a).
 *
 * AI-1192: Add-Content modal Cancel button 44px touch target
 * AI-1194: Quick AI Edit toggle buttons 44px touch target
 * AI-1198: Tools dropdown action items use semantic button elements
 * AI-1201: PageChip "Untitled" fallback replaced with "Homepage" / "(No title)"
 * AI-1204: Predefined Styles section label font-size bumped to 12px
 */
class Round51Fb049aAI1192AI1194AI1198AI1201AI1204ContractTest extends TestCase
{
    private string $generalStylesSrc;
    private string $liveEditInputSrc;
    private string $toolbarToolsDropdownSrc;
    private string $pageChipSrc;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $root = self::projectRoot();

        $this->generalStylesSrc = (string) file_get_contents(
            $root . '/packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        );
        $this->liveEditInputSrc = (string) file_get_contents(
            $root . '/packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-input.css'
        );
        $this->toolbarToolsDropdownSrc = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/ui/components/Toolbar/ToolbarToolsDropdown.vue'
        );
        $this->pageChipSrc = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/ui/components/Toolbar/PageChip.vue'
        );
    }

    // ── AI-1192: Cancel button touch target ────────────────────────────

    public function test_ai1192_cancel_button_touch_target_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-content-picker-modal\s+\.fi-modal-footer-actions\s+\.fi-btn\s*\{[^}]*min-height:\s*44px/s',
            $this->generalStylesSrc,
            'AI-1192: Cancel button must have min-height: 44px touch target'
        );
    }

    // ── AI-1194: Quick AI Edit toggle touch target ─────────────────────

    public function test_ai1194_quick_edit_toggle_touch_target_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '/#mw-live-edit-quickEditComponent-box\s+\.btn\s*\{[^}]*min-height:\s*44px/s',
            $this->generalStylesSrc,
            'AI-1194: Quick AI Edit toggle buttons must have min-height: 44px'
        );
    }

    // ── AI-1198: Semantic button elements in tools dropdown ────────────

    /**
     * @dataProvider actionItemsProvider
     */
    public function test_ai1198_action_items_use_button_elements(string $handler): void
    {
        $this->assertMatchesRegularExpression(
            '/<button\s+type="button"\s+[^>]*@click="' . preg_quote($handler, '/') . '"/',
            $this->toolbarToolsDropdownSrc,
            "AI-1198: {$handler} must be rendered as <button type=\"button\">"
        );
    }

    public static function actionItemsProvider(): array
    {
        return [
            'Insert Layout' => ['handleInsertLayout'],
            'Insert Module' => ['handleInsertModule'],
            'Edit Element'  => ['handleEditTextNode'],
            'Module Settings' => ['handleEditModuleNode'],
            'Templates & layouts' => ['handleTemplateSettings'],
            'Design' => ['handleStyleEditor'],
            'Quick AI Edit' => ['handleQuickEdit'],
            'Layout Settings' => ['handleCurrentLayoutSettings'],
            'Setup Wizard' => ['openSetupWizard'],
            'More Settings toggle' => ['toggleMoreSettings'],
            'Dark mode toggle' => ['handleToggleDarkMode'],
            'Layers' => ['handleLayers'],
            'Code Editor' => ['showCodeEditor'],
            'Reset Content' => ['openContentResetContent'],
            'Clear Cache' => ['clearCache'],
        ];
    }

    public function test_ai1198_user_menu_items_stay_as_anchor_elements(): void
    {
        $this->assertMatchesRegularExpression(
            '/v-for="[^"]*userMenuItems[^"]*"[\s\S]*?<a\s/',
            $this->toolbarToolsDropdownSrc,
            'AI-1198: User menu items with href must stay as <a> elements'
        );
    }

    public function test_ai1198_css_covers_both_anchor_and_button_selectors(): void
    {
        $this->assertStringContainsString(
            '.dropdown-content li button',
            $this->toolbarToolsDropdownSrc,
            'AI-1198: CSS must have selectors covering button elements'
        );
    }

    public function test_ai1198_button_reset_styles_present(): void
    {
        $styleSection = '';
        if (preg_match('/<style[^>]*>([\s\S]*?)<\/style>/', $this->toolbarToolsDropdownSrc, $m)) {
            $styleSection = $m[1];
        }

        $this->assertStringContainsString('background: none', $styleSection, 'AI-1198: button reset must include background: none');
        $this->assertStringContainsString('border: none', $styleSection, 'AI-1198: button reset must include border: none');
        $this->assertStringContainsString('width: 100%', $styleSection, 'AI-1198: button reset must include width: 100%');
        $this->assertStringContainsString('text-align: left', $styleSection, 'AI-1198: button reset must include text-align: left');
    }

    public function test_ai1198_dark_mode_covers_button_selectors(): void
    {
        $this->assertMatchesRegularExpression(
            '/:global\(\.dark\)\s+\.dropdown-content\s+li\s+button/',
            $this->toolbarToolsDropdownSrc,
            'AI-1198: Dark mode CSS must cover button selectors'
        );
    }

    public function test_ai1198_submenu_covers_button_selectors(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.submenu\s+li\s+button/',
            $this->toolbarToolsDropdownSrc,
            'AI-1198: Submenu CSS must cover button selectors'
        );
    }

    // ── AI-1201: PageChip fallback labels ──────────────────────────────

    public function test_ai1201_chip_label_defaults_to_homepage(): void
    {
        $this->assertStringContainsString(
            "'Homepage'",
            $this->pageChipSrc,
            'AI-1201: PageChip chip label must default to Homepage'
        );
    }

    public function test_ai1201_list_item_fallback_is_no_title(): void
    {
        $this->assertStringContainsString(
            "'(No title)'",
            $this->pageChipSrc,
            'AI-1201: PageChip list item fallback must be (No title)'
        );
    }

    public function test_ai1201_untitled_label_absent(): void
    {
        $stripped = preg_replace('~/\*.*?\*/~s', '', $this->pageChipSrc);
        $stripped = preg_replace('~//[^\n]*~', '', $stripped);
        $stripped = preg_replace('~<!--[\s\S]*?-->~', '', $stripped);

        $this->assertStringNotContainsString(
            "'Untitled'",
            $stripped,
            'AI-1201: Legacy Untitled fallback must be absent from executable source'
        );
    }

    // ── AI-1204: Predefined Styles label font-size ─────────────────────

    public function test_ai1204_label_font_size_is_12px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.live-edit-label\s*\{[^}]*font-size:\s*12px/s',
            $this->liveEditInputSrc,
            'AI-1204: .live-edit-label must have font-size: 12px'
        );
    }

    public function test_ai1204_old_font_size_absent(): void
    {
        $stripped = preg_replace('~/\*.*?\*/~s', '', $this->liveEditInputSrc);

        $this->assertDoesNotMatchRegularExpression(
            '/\.live-edit-label\s*\{[^}]*font-size:\s*9\.75px/s',
            $stripped,
            'AI-1204: Legacy 9.75px font-size must not be present'
        );
    }
}
