<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1146 / task-2026-05-28-af5d5b Batch 1 #5.
 *
 * Scope: Element Style Editor (ESE) toggle rows — each accordion
 * trigger must be keyboard-operable AND meet WCAG 2.5.5 touch target.
 *
 * Implementation:
 *   - Wrapper `<div class="element-style-editor-toggle-wrapper">` already
 *     carries role="button" + tabindex="0" + aria-label + aria-expanded
 *     + @keydown.enter.prevent + @keydown.space.prevent (prior tickets).
 *     Equivalent semantic to a native <button> without the invalid-HTML
 *     hazard of nesting interactive child controls (sliders, colour
 *     pickers, dropdowns) inside a <button>.
 *   - Header row `.d-flex:has(> svg)` gets min-height: 44px so the
 *     click target meets WCAG 2.5.5 regardless of inner icon/label size.
 */
class Ese1146AccessibilityTouchTargetContractTest extends TestCase
{
    private string $eseAppVue;
    private string $generalStylesCss;
    private string $builtThemeCss;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $root = self::projectRoot();

        $this->eseAppVue = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorApp.vue'
        );

        $this->generalStylesCss = (string) file_get_contents(
            $root . '/packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        );

        $this->builtThemeCss = (string) file_get_contents(
            $root . '/public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        );
    }

    // --- Vue source: existing ARIA + keyboard support ---

    public function test_toggle_wrappers_carry_role_button(): void
    {
        $count = preg_match_all('/element-style-editor-toggle-wrapper[\s\S]{0,500}?role="button"/', $this->eseAppVue);
        $this->assertGreaterThanOrEqual(
            10,
            $count,
            'AI-1146: at least 10 ESE toggle wrappers must carry role="button" (found ' . $count . ')'
        );
    }

    public function test_toggle_wrappers_carry_tabindex_zero(): void
    {
        $count = preg_match_all('/element-style-editor-toggle-wrapper[\s\S]{0,500}?tabindex="0"/', $this->eseAppVue);
        $this->assertGreaterThanOrEqual(
            10,
            $count,
            'AI-1146: at least 10 ESE toggle wrappers must carry tabindex="0" (found ' . $count . ')'
        );
    }

    public function test_toggle_wrappers_carry_aria_expanded(): void
    {
        $count = preg_match_all('/element-style-editor-toggle-wrapper[\s\S]{0,600}?:aria-expanded=/', $this->eseAppVue);
        $this->assertGreaterThanOrEqual(
            10,
            $count,
            'AI-1146: at least 10 ESE toggle wrappers must carry :aria-expanded binding (found ' . $count . ')'
        );
    }

    public function test_toggle_wrappers_carry_keydown_enter_handler(): void
    {
        $count = preg_match_all('/element-style-editor-toggle-wrapper[\s\S]{0,800}?@keydown\.enter\.prevent=/', $this->eseAppVue);
        $this->assertGreaterThanOrEqual(
            10,
            $count,
            'AI-1146: at least 10 ESE toggle wrappers must handle keydown.enter (found ' . $count . ')'
        );
    }

    public function test_toggle_wrappers_carry_keydown_space_handler(): void
    {
        $count = preg_match_all('/element-style-editor-toggle-wrapper[\s\S]{0,800}?@keydown\.space\.prevent=/', $this->eseAppVue);
        $this->assertGreaterThanOrEqual(
            10,
            $count,
            'AI-1146: at least 10 ESE toggle wrappers must handle keydown.space (found ' . $count . ')'
        );
    }

    // --- CSS source: 44px touch target on header row ---

    public function test_toggle_header_row_min_height_44px_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.element-style-editor-toggle-wrapper\s*\{[\s\S]*?&\s*\.d-flex:has\(>\s*svg\)\s*\{[^}]*min-height:\s*44px/',
            $this->generalStylesCss,
            'AI-1146: .element-style-editor-toggle-wrapper .d-flex:has(> svg) must carry min-height: 44px (touch target)'
        );
    }

    public function test_toggle_header_row_min_height_44px_in_built_bundle(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.element-style-editor-toggle-wrapper\s*\{[\s\S]*?\.d-flex:has\(>\s*svg\)\s*\{[^}]*min-height:\s*44px/',
            $this->builtThemeCss,
            'AI-1146: built theme bundle must contain min-height: 44px on the ESE header row'
        );
    }
}
