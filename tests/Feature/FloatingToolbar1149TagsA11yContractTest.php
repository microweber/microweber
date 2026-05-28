<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1149 / task-2026-05-28-af5d5b Batch 1 #7.
 *
 * Scope (dispatch verbatim): "Floating toolbar custom tags (<MW-LE-ELEMENT>)
 * zero keyboard/a11y. Fix: role='button', aria-label, tabindex='0'."
 *
 * Status: covered by prior AI-1171 / task-2026-05-27-df6d28 ship.
 *
 * Resolution note: the dispatch names the bare <mw-le-element> custom tag,
 * but that tag is the generic ElementManager root (defined in
 * packages/frontend-assets/resources/assets/api-core/core/classes/element.js
 * around line 512 via customElements.define). The actual interactive surface
 * inside the floating toolbar is the .mw-le-handle-menu-button class buttons
 * produced by the this.button factory in handle-menu.js. AI-1171 already
 * pinned role='button' + tabindex='0' + aria-label (sourced from conf.title)
 * + Enter/Space keydown handler on those buttons — which is the correct
 * placement (semantic a11y belongs on the button element, not the bare
 * custom-element root).
 *
 * This contract test pins the AI-1171 implementation + cross-pipeline
 * coverage (Vite frontend-assets live-edit-app.js bundle).
 */
class FloatingToolbar1149TagsA11yContractTest extends TestCase
{
    private string $handleMenuJs;
    private string $builtLiveEditAppJs;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $root = self::projectRoot();

        $this->handleMenuJs = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/api-core/core/handle-menu.js'
        );

        $this->builtLiveEditAppJs = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/dist/build/live-edit-app.js'
        );
    }

    // --- Source: AI-1171 a11y attribute assignment on this.button factory ---

    public function test_button_factory_sets_role_button_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            "/btnNode\.setAttribute\(\s*'role'\s*,\s*'button'\s*\)/",
            $this->handleMenuJs,
            'AI-1149: handle-menu.js this.button must set role="button" on the button node'
        );
    }

    public function test_button_factory_sets_tabindex_zero_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            "/btnNode\.setAttribute\(\s*'tabindex'\s*,\s*'0'\s*\)/",
            $this->handleMenuJs,
            'AI-1149: handle-menu.js this.button must set tabindex="0" on the button node'
        );
    }

    public function test_button_factory_sets_aria_label_from_title_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            "/btnNode\.setAttribute\(\s*'aria-label'\s*,\s*label\s*\)/",
            $this->handleMenuJs,
            'AI-1149: handle-menu.js this.button must set aria-label sourced from conf.title'
        );
    }

    public function test_button_factory_wires_enter_space_keydown_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            "/btn\.on\(\s*'keydown'[\s\S]{0,400}e\.key\s*===\s*'Enter'\s*\|\|\s*e\.key\s*===\s*' '/",
            $this->handleMenuJs,
            'AI-1149: handle-menu.js this.button must wire keydown handler for Enter + Space keys'
        );
    }

    public function test_button_factory_keydown_calls_click_in_source(): void
    {
        $this->assertMatchesRegularExpression(
            "/e\.key\s*===\s*'Enter'\s*\|\|\s*e\.key\s*===\s*' '[\s\S]{0,200}preventDefault\(\)[\s\S]{0,200}btnNode\.click\(\)/",
            $this->handleMenuJs,
            'AI-1149: handle-menu.js keydown handler must preventDefault + dispatch click on Enter/Space'
        );
    }

    public function test_ai_1171_lineage_marker_preserved_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-27-df6d28 / AI-1171',
            $this->handleMenuJs,
            'AI-1149: AI-1171 lineage marker comment must be preserved adjacent to the this.button factory'
        );
    }

    // --- Built bundle: cross-pipeline regression guard ---

    public function test_role_button_attribute_present_in_built_bundle(): void
    {
        $this->assertMatchesRegularExpression(
            '/setAttribute\(\s*"role"\s*,\s*"button"\s*\)/',
            $this->builtLiveEditAppJs,
            'AI-1149: built live-edit-app.js must contain setAttribute("role","button") from AI-1171'
        );
    }

    public function test_tabindex_zero_attribute_present_in_built_bundle(): void
    {
        $this->assertMatchesRegularExpression(
            '/setAttribute\(\s*"tabindex"\s*,\s*"0"\s*\)/',
            $this->builtLiveEditAppJs,
            'AI-1149: built live-edit-app.js must contain setAttribute("tabindex","0") from AI-1171'
        );
    }

    public function test_aria_label_attribute_present_in_built_bundle(): void
    {
        $this->assertMatchesRegularExpression(
            '/setAttribute\(\s*"aria-label"\s*,/',
            $this->builtLiveEditAppJs,
            'AI-1149: built live-edit-app.js must contain setAttribute("aria-label", ...) from AI-1171'
        );
    }
}
