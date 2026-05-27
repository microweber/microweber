<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Contract test for AI-1211 / task-2026-05-27-b7f7a9.
 *
 * Pins WCAG keyboard accessibility attributes on the inline text editor
 * toolbar (mw-editor-button) and floating element handle buttons
 * (mw-le-handle-menu-button).
 *
 * Layer A: MWEditor.core.button() sets role, tabindex, aria-label, title,
 *          and keydown Enter/Space handler on every mw-editor-button.
 * Layer B: handle-menu.js button() sets tabindex and keydown handler on
 *          every mw-le-handle-menu-button, and click guards allow e.which===0
 *          (keyboard-triggered clicks).
 */
class EditorB7f7a9AI1211TextEditorToolbarWcagContractTest extends TestCase
{
    private string $coreSource;
    private string $handleMenuSource;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        $root = self::projectRoot();
        $this->coreSource = (string) file_get_contents(
            $root . '/packages/frontend-assets-libs/resources/local-libs/api/editor/core.js'
        );
        $this->handleMenuSource = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/api-core/core/handle-menu.js'
        );
    }

    public function test_core_button_sets_role_attribute(): void
    {
        $this->assertStringContainsString(
            "node.setAttribute('role', 'button')",
            $this->coreSource
        );
    }

    public function test_core_button_sets_tabindex_attribute(): void
    {
        $this->assertStringContainsString(
            "node.setAttribute('tabindex', '0')",
            $this->coreSource
        );
    }

    public function test_core_button_sets_aria_label_from_tooltip(): void
    {
        $this->assertStringContainsString(
            "node.setAttribute('aria-label', settings.props.tooltip)",
            $this->coreSource
        );
    }

    public function test_core_button_sets_title_from_tooltip(): void
    {
        $this->assertStringContainsString(
            "node.setAttribute('title', settings.props.tooltip)",
            $this->coreSource
        );
    }

    public function test_core_button_has_keydown_enter_space_handler(): void
    {
        $this->assertStringContainsString("e.key === 'Enter'", $this->coreSource);
        $this->assertStringContainsString("e.key === ' '", $this->coreSource);
        $this->assertStringContainsString('node.click()', $this->coreSource);
    }

    public function test_handle_menu_button_sets_tabindex(): void
    {
        $this->assertStringContainsString(
            "btnNode.setAttribute('tabindex', '0')",
            $this->handleMenuSource
        );
    }

    public function test_handle_menu_button_has_keydown_handler(): void
    {
        $this->assertStringContainsString("e.key === 'Enter'", $this->handleMenuSource);
        $this->assertStringContainsString("e.key === ' '", $this->handleMenuSource);
        $this->assertStringContainsString('btnNode.click()', $this->handleMenuSource);
    }

    public function test_handle_menu_click_guards_allow_keyboard_triggered_clicks(): void
    {
        $this->assertStringContainsString('e.which !== 0', $this->handleMenuSource);
    }

    public function test_core_dist_matches_source(): void
    {
        $distSource = (string) file_get_contents(
            self::projectRoot() . '/packages/frontend-assets-libs/resources/dist/api/editor/core.js'
        );
        $this->assertSame(
            $this->coreSource,
            $distSource,
            'dist/api/editor/core.js must be byte-identical to local-libs source'
        );
    }

    /**
     * Verify all 19 editor controllers pass a tooltip prop.
     */
    public function test_all_editor_controllers_have_tooltip(): void
    {
        $controllersSource = (string) file_get_contents(
            self::projectRoot() . '/packages/frontend-assets-libs/resources/local-libs/api/editor/controllers.js'
        );

        preg_match_all('/tooltip:\s/', $controllersSource, $matches);
        $tooltipCount = count($matches[0]);

        $this->assertGreaterThanOrEqual(
            19,
            $tooltipCount,
            "Expected at least 19 tooltip assignments in controllers.js, found {$tooltipCount}"
        );
    }
}
