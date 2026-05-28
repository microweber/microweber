<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1150 / task-2026-05-28-af5d5b Batch 1 #8.
 *
 * Scope (dispatch verbatim): "Floating toolbar touch targets 32x32px.
 * Fix: min-width/min-height 44px."
 *
 * Lineage:
 *   - Prior AI-1170 / task-2026-05-27-df6d28 already bumped the nested
 *     .mw-handle-item .mw-le-handle-menu-button selector (inside
 *     .mw-handle-item-menus-holder) from 32x32 to 44x44.
 *   - AI-1150 extends the same fix to the top-level
 *     .mw-le-handle-menu-buttons > .mw-le-handle-menu-button rule which
 *     governs every floating toolbar button strip, adding the
 *     dispatch-named min-width/min-height 44px belt-and-braces.
 *
 * This contract pins the AI-1150 source rule + AI-1170 lineage rule +
 * cross-pipeline built bundle (Vite frontend-assets liveedit.css).
 */
class FloatingToolbar1150TouchTargetContractTest extends TestCase
{
    private string $handlesScss;
    private string $builtLiveEditCss;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $root = self::projectRoot();

        $this->handlesScss = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/css/scss/handles.scss'
        );

        $this->builtLiveEditCss = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/dist/build/liveedit.css'
        );
    }

    // --- AI-1150 source: top-level floating toolbar button rule ---

    public function test_top_level_button_rule_has_width_44px_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/&\s*>\s*\.mw-le-handle-menu-button\s*\{[^}]*width:\s*44px/s',
            $this->handlesScss,
            'AI-1150: .mw-le-handle-menu-buttons > .mw-le-handle-menu-button must carry width: 44px'
        );
    }

    public function test_top_level_button_rule_has_height_44px_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/&\s*>\s*\.mw-le-handle-menu-button\s*\{[^}]*height:\s*44px/s',
            $this->handlesScss,
            'AI-1150: .mw-le-handle-menu-buttons > .mw-le-handle-menu-button must carry height: 44px'
        );
    }

    public function test_top_level_button_rule_has_min_width_44px_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/&\s*>\s*\.mw-le-handle-menu-button\s*\{[^}]*min-width:\s*44px/s',
            $this->handlesScss,
            'AI-1150: .mw-le-handle-menu-buttons > .mw-le-handle-menu-button must carry min-width: 44px (dispatch verbatim)'
        );
    }

    public function test_top_level_button_rule_has_min_height_44px_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/&\s*>\s*\.mw-le-handle-menu-button\s*\{[^}]*min-height:\s*44px/s',
            $this->handlesScss,
            'AI-1150: .mw-le-handle-menu-buttons > .mw-le-handle-menu-button must carry min-height: 44px (dispatch verbatim)'
        );
    }

    public function test_ai_1150_task_id_marker_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-af5d5b / AI-1150',
            $this->handlesScss,
            'AI-1150: task-id marker comment must be present adjacent to the fixed rule'
        );
    }

    // --- AI-1150 negative regression guard: legacy 32px must be gone ---

    public function test_top_level_button_rule_has_no_legacy_32px_dimension_in_source(): void
    {
        if (! preg_match('/(&\s*>\s*\.mw-le-handle-menu-button\s*\{[^}]*\})/s', $this->handlesScss, $m)) {
            $this->fail('Could not locate the top-level button rule slice for assertion');
        }
        $slice = $m[1];

        $this->assertDoesNotMatchRegularExpression(
            '/\b(?:width|height):\s*32px/',
            $slice,
            'AI-1150: legacy 32x32 width/height must be removed from the top-level button rule'
        );
    }

    // --- AI-1170 lineage: nested handle-item path already at 44x44 ---

    public function test_ai_1170_nested_button_rule_still_carries_44px_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/AI-1170[\s\S]{0,200}\.mw-le-handle-menu-button\s*\{[^}]*width:\s*44px[^}]*height:\s*44px/',
            $this->handlesScss,
            'AI-1150: prior AI-1170 ship at the nested .mw-handle-item .mw-le-handle-menu-button rule must remain at 44x44 (lineage guard)'
        );
    }

    // --- Built bundle: cross-pipeline regression guard ---

    public function test_top_level_button_44px_present_in_built_bundle(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-handle-menu-buttons\s*>\s*\.mw-le-handle-menu-button\s*\{[^}]*width:\s*44px[^}]*height:\s*44px[^}]*min-width:\s*44px[^}]*min-height:\s*44px/',
            $this->builtLiveEditCss,
            'AI-1150: built liveedit.css bundle must contain the .mw-le-handle-menu-buttons>.mw-le-handle-menu-button 44x44 rule with all four dimension properties'
        );
    }

    public function test_built_bundle_does_not_contain_legacy_32px_on_top_level_button(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/\.mw-le-handle-menu-buttons\s*>\s*\.mw-le-handle-menu-button\s*\{[^}]*\b(?:width|height):\s*32px/',
            $this->builtLiveEditCss,
            'AI-1150: built bundle must not regress to legacy 32x32 on the top-level button'
        );
    }
}
