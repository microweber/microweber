<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1157 + AI-1158 / task-2026-05-28-af5d5b Batch 1 #3 + #4.
 *
 * Scope (grouped per dispatch rule "Group by component when possible"):
 *   - AI-1157: Insert Layout modal close button 19×32 below WCAG 2.5.5 44px.
 *     Status: covered by AI-1185 / task-2026-05-28-10c8ab — .mw-le-dialog-close-btn
 *     in packages/frontend-assets/resources/assets/ui/css/index.css carries
 *     min-width: 44px + min-height: 44px. This test pins the existing rule.
 *
 *   - AI-1158: Insert Layout layout cards are plain divs (not keyboard-focusable).
 *     Pre-fix: role="button" + aria-label only (added by AI-1167 /
 *     task-2026-05-27-de93c9). MISSING: tabindex="0" + keyboard handlers.
 *     This ship adds tabindex="0" + Enter/Space keydown handlers on both
 *     masonry-view AND list-view card variants in ListLayouts.vue.
 */
class InsertLayout1157_1158AccessibilityContractTest extends TestCase
{
    private string $indexCss;
    private string $listLayoutsVue;
    private string $builtLiveEditJs;

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

        $this->builtLiveEditJs = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/dist/build/live-edit-app.js'
        );
    }

    // --- AI-1157 (covered by AI-1185 / task-2026-05-28-10c8ab) ---

    public function test_dialog_close_btn_min_width_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-dialog-close-btn\s*\{[^}]*min-width:\s*44px/s',
            $this->indexCss,
            'AI-1157: .mw-le-dialog-close-btn must have min-width: 44px (lineage AI-1185)'
        );
    }

    public function test_dialog_close_btn_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-dialog-close-btn\s*\{[^}]*min-height:\s*44px/s',
            $this->indexCss,
            'AI-1157: .mw-le-dialog-close-btn must have min-height: 44px (lineage AI-1185)'
        );
    }

    // --- AI-1158 source-level pins ---

    public function test_masonry_card_has_role_button(): void
    {
        $this->assertMatchesRegularExpression(
            "/'modules-list-block-item-masonry'[\s\S]{0,400}role=\"button\"/",
            $this->listLayoutsVue,
            'AI-1158: masonry-view layout card must carry role="button"'
        );
    }

    public function test_masonry_card_has_tabindex_zero(): void
    {
        $this->assertMatchesRegularExpression(
            "/'modules-list-block-item-masonry'[\s\S]{0,500}tabindex=\"0\"/",
            $this->listLayoutsVue,
            'AI-1158: masonry-view layout card must carry tabindex="0" for keyboard focus'
        );
    }

    public function test_masonry_card_has_enter_keydown_handler(): void
    {
        $this->assertMatchesRegularExpression(
            "/'modules-list-block-item-masonry'[\s\S]{0,800}v-on:keydown\.enter\.prevent=\"insertLayout\(item\)\"/",
            $this->listLayoutsVue,
            'AI-1158: masonry-view card must activate on Enter via insertLayout()'
        );
    }

    public function test_masonry_card_has_space_keydown_handler(): void
    {
        $this->assertMatchesRegularExpression(
            "/'modules-list-block-item-masonry'[\s\S]{0,800}v-on:keydown\.space\.prevent=\"insertLayout\(item\)\"/",
            $this->listLayoutsVue,
            'AI-1158: masonry-view card must activate on Space via insertLayout()'
        );
    }

    public function test_list_card_has_tabindex_zero(): void
    {
        $this->assertMatchesRegularExpression(
            "/'modules-list-block-style-'\s*\+\s*layoutsListTypePreview[\s\S]{0,500}tabindex=\"0\"/",
            $this->listLayoutsVue,
            'AI-1158: list-view layout card must carry tabindex="0" for keyboard focus'
        );
    }

    public function test_list_card_has_enter_keydown_handler(): void
    {
        $this->assertMatchesRegularExpression(
            "/'modules-list-block-style-'\s*\+\s*layoutsListTypePreview[\s\S]{0,800}v-on:keydown\.enter\.prevent=\"insertLayout\(item\)\"/",
            $this->listLayoutsVue,
            'AI-1158: list-view card must activate on Enter via insertLayout()'
        );
    }

    public function test_list_card_has_space_keydown_handler(): void
    {
        $this->assertMatchesRegularExpression(
            "/'modules-list-block-style-'\s*\+\s*layoutsListTypePreview[\s\S]{0,800}v-on:keydown\.space\.prevent=\"insertLayout\(item\)\"/",
            $this->listLayoutsVue,
            'AI-1158: list-view card must activate on Space via insertLayout()'
        );
    }

    // --- AI-1158 built-bundle regression guard ---

    public function test_built_bundle_carries_tabindex_zero_on_layout_card(): void
    {
        $this->assertStringContainsString(
            'tabindex:"0"',
            $this->builtLiveEditJs,
            'AI-1158: built live-edit-app.js must contain tabindex:"0" attribute'
        );
    }

    public function test_built_bundle_wires_keydown_to_insert_layout(): void
    {
        // Vue compiler emits onKeydown:[withModifiers(withKeys(handler, ...), ...)]
        // shape; minified to onKeydown:[oe(ce(<arrow>=>...insertLayout...))]
        $this->assertMatchesRegularExpression(
            '/onKeydown:\[[a-zA-Z_$]+\([a-zA-Z_$]+\([^)]+=>[^)]+insertLayout/',
            $this->builtLiveEditJs,
            'AI-1158: built bundle must wire keydown via withKeys+withModifiers to insertLayout()'
        );
    }
}
