<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1185 / task-2026-05-28-10c8ab.
 *
 * P2: Insert Layout modal close button measured 19x32px and
 * "All Categories" tab height defaulted to ~40px — both below
 * the WCAG 2.5.5 44x44px minimum touch target.
 *
 * Fix: CSS rules in index.css adding min-width/min-height 44px
 * to .mw-le-dialog-close-btn and min-height 44px to category
 * tab li[role="tab"] items.
 */
class LiveEdit10c8abAI1185InsertLayoutTouchTargetsContractTest extends TestCase
{
    private string $indexCss;
    private string $listLayoutsVue;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $root = self::projectRoot();

        $this->indexCss = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/ui/css/index.css'
        );

        $this->listLayoutsVue = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/ui/components/Layouts/ListLayouts.vue'
        );
    }

    public function test_close_button_min_width_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-dialog-close-btn\s*\{[^}]*min-width:\s*44px/s',
            $this->indexCss,
            'AI-1185: close button must have min-width: 44px'
        );
    }

    public function test_close_button_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-dialog-close-btn\s*\{[^}]*min-height:\s*44px/s',
            $this->indexCss,
            'AI-1185: close button must have min-height: 44px'
        );
    }

    public function test_close_button_display_inline_flex(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-dialog-close-btn\s*\{[^}]*display:\s*inline-flex/s',
            $this->indexCss,
            'AI-1185: close button must have display: inline-flex for centering'
        );
    }

    public function test_close_button_align_items_center(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-dialog-close-btn\s*\{[^}]*align-items:\s*center/s',
            $this->indexCss,
            'AI-1185: close button must have align-items: center'
        );
    }

    public function test_close_button_justify_content_center(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-dialog-close-btn\s*\{[^}]*justify-content:\s*center/s',
            $this->indexCss,
            'AI-1185: close button must have justify-content: center'
        );
    }

    public function test_category_tab_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-dialog\s+\.modules-list-categories\s+li\[role="tab"\]\s*\{[^}]*min-height:\s*44px/s',
            $this->indexCss,
            'AI-1185: category tab items must have min-height: 44px'
        );
    }

    public function test_category_tab_display_flex(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-dialog\s+\.modules-list-categories\s+li\[role="tab"\]\s*\{[^}]*display:\s*flex/s',
            $this->indexCss,
            'AI-1185: category tab items must have display: flex for vertical centering'
        );
    }

    public function test_category_tab_align_items_center(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-dialog\s+\.modules-list-categories\s+li\[role="tab"\]\s*\{[^}]*align-items:\s*center/s',
            $this->indexCss,
            'AI-1185: category tab items must have align-items: center'
        );
    }

    public function test_close_button_has_class_in_template(): void
    {
        $this->assertStringContainsString(
            'class="mw-le-dialog-close-btn"',
            $this->listLayoutsVue,
            'AI-1185: close button must have the mw-le-dialog-close-btn class'
        );
    }

    public function test_close_button_has_aria_label(): void
    {
        $this->assertStringContainsString(
            'aria-label',
            $this->listLayoutsVue,
            'AI-1185: close button must have an aria-label'
        );
    }

    public function test_category_tabs_have_role_tab(): void
    {
        $this->assertStringContainsString(
            'role="tab"',
            $this->listLayoutsVue,
            'AI-1185: category list items must have role="tab"'
        );
    }

    public function test_built_css_contains_close_button_rule(): void
    {
        $builtCss = (string) file_get_contents(
            self::projectRoot() . '/packages/frontend-assets/resources/dist/build/live-edit-app.css'
        );

        $this->assertStringContainsString(
            'min-width:44px',
            $builtCss,
            'AI-1185: built CSS must contain the 44px min-width for the close button'
        );
    }

    public function test_built_css_contains_category_tab_rule(): void
    {
        $builtCss = (string) file_get_contents(
            self::projectRoot() . '/packages/frontend-assets/resources/dist/build/live-edit-app.css'
        );

        $this->assertStringContainsString(
            'min-height:44px',
            $builtCss,
            'AI-1185: built CSS must contain the 44px min-height for category tabs'
        );
    }
}
