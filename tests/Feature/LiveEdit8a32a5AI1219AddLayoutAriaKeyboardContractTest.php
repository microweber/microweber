<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1219 / task-2026-05-28-8a32a5.
 *
 * P2: ADD LAYOUT between-section buttons (plusTop / plusBottom) were custom
 * tags with no ARIA attributes — invisible to keyboard nav and screen readers.
 * Fix: role="button", aria-label, tabindex="0", Enter/Space keydown handler.
 */
class LiveEdit8a32a5AI1219AddLayoutAriaKeyboardContractTest extends TestCase
{
    private string $layoutJs;
    private string $builtJs;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $root = self::projectRoot();

        $this->layoutJs = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/api-core/core/handles-content/layout.js'
        );

        $this->builtJs = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/dist/build/live-edit-app.js'
        );
    }

    public function test_plus_top_has_role_button(): void
    {
        $this->assertStringContainsString(
            "attr('role', 'button')",
            $this->layoutJs,
            'AI-1219: plusTop must have role="button"'
        );
    }

    public function test_plus_top_has_aria_label(): void
    {
        $this->assertStringContainsString(
            "attr('aria-label', 'Add layout above section')",
            $this->layoutJs,
            'AI-1219: plusTop must have descriptive aria-label'
        );
    }

    public function test_plus_bottom_has_aria_label(): void
    {
        $this->assertStringContainsString(
            "attr('aria-label', 'Add layout below section')",
            $this->layoutJs,
            'AI-1219: plusBottom must have descriptive aria-label'
        );
    }

    public function test_plus_top_has_tabindex_zero(): void
    {
        $this->assertMatchesRegularExpression(
            '/plusTop\s*=\s*ElementManager\(\{[^}]*tabIndex:\s*0/s',
            $this->layoutJs,
            'AI-1219: plusTop must have tabIndex: 0 for keyboard focus'
        );
    }

    public function test_plus_bottom_has_tabindex_zero(): void
    {
        $this->assertMatchesRegularExpression(
            '/plusBottom\s*=\s*ElementManager\(\{[^}]*tabIndex:\s*0/s',
            $this->layoutJs,
            'AI-1219: plusBottom must have tabIndex: 0 for keyboard focus'
        );
    }

    public function test_enter_key_handler_exists(): void
    {
        $this->assertStringContainsString(
            "e.key === 'Enter'",
            $this->layoutJs,
            'AI-1219: keydown handler must respond to Enter key'
        );
    }

    public function test_space_key_handler_exists(): void
    {
        $this->assertStringContainsString(
            "e.key === ' '",
            $this->layoutJs,
            'AI-1219: keydown handler must respond to Space key'
        );
    }

    public function test_prevent_default_on_keydown(): void
    {
        $this->assertStringContainsString(
            'e.preventDefault()',
            $this->layoutJs,
            'AI-1219: keydown handler must call preventDefault to suppress scroll on Space'
        );
    }

    public function test_keydown_handler_wired_to_plus_top(): void
    {
        $this->assertMatchesRegularExpression(
            '/plusTop.*\.on\(\s*[\'"]keydown[\'"]/s',
            $this->layoutJs,
            'AI-1219: plusTop must have keydown event listener'
        );
    }

    public function test_keydown_handler_wired_to_plus_bottom(): void
    {
        $this->assertMatchesRegularExpression(
            '/plusBottom.*\.on\(\s*[\'"]keydown[\'"]/s',
            $this->layoutJs,
            'AI-1219: plusBottom must have keydown event listener'
        );
    }

    public function test_built_bundle_contains_role_button(): void
    {
        $this->assertStringContainsString(
            'role","button"',
            $this->builtJs,
            'AI-1219: built JS bundle must contain role="button" attribute setting'
        );
    }

    public function test_built_bundle_contains_aria_labels(): void
    {
        $this->assertStringContainsString(
            'Add layout above section',
            $this->builtJs,
            'AI-1219: built JS bundle must contain "Add layout above section" label'
        );

        $this->assertStringContainsString(
            'Add layout below section',
            $this->builtJs,
            'AI-1219: built JS bundle must contain "Add layout below section" label'
        );
    }

    public function test_built_bundle_contains_keydown_handlers(): void
    {
        $plusTopPos = strpos($this->builtJs, 'Add layout above section');
        $this->assertNotFalse($plusTopPos);

        $region = substr($this->builtJs, $plusTopPos, 2000);
        $keydownCount = substr_count($region, '.on("keydown"');

        $this->assertGreaterThanOrEqual(
            2,
            $keydownCount,
            'AI-1219: built bundle must contain keydown handlers for both plus buttons'
        );
    }
}
