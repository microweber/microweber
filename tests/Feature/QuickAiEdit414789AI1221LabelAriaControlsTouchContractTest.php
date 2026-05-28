<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1221 / task-2026-05-28-414789.
 *
 * Quick AI Edit panel had three bundled a11y failures:
 *  1. Label for= did not match input id= (broken association for 22 inputs)
 *  2. Accordion headers had aria-expanded but no aria-controls (19 toggles)
 *  3. Submit button height 40px < 44px WCAG minimum
 */
class QuickAiEdit414789AI1221LabelAriaControlsTouchContractTest extends TestCase
{
    private string $quickAiEditJs;
    private string $aiChatJs;
    private string $builtJs;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $root = self::projectRoot();

        $this->quickAiEditJs = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/components/quick-ai-edit.js'
        );

        $this->aiChatJs = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/components/ai-chat.js'
        );

        $this->builtJs = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/dist/build/live-edit-app.js'
        );
    }

    // --- Finding 1: Label-input association ---

    public function test_label_has_for_attribute_matching_input_id(): void
    {
        $this->assertMatchesRegularExpression(
            '/for="data-node-id-\$\{obj\.id\}"/',
            $this->quickAiEditJs,
            'AI-1221: label must have for= matching input id="data-node-id-${obj.id}"'
        );
    }

    public function test_label_for_and_input_id_use_same_pattern(): void
    {
        $this->assertMatchesRegularExpression(
            '/<label[^>]*for="data-node-id-\$\{obj\.id\}"/',
            $this->quickAiEditJs,
            'AI-1221: <label> element must carry for="data-node-id-${obj.id}"'
        );

        $this->assertMatchesRegularExpression(
            '/<input[^>]*id="data-node-id-\$\{obj\.id\}"/',
            $this->quickAiEditJs,
            'AI-1221: <input> element must carry id="data-node-id-${obj.id}"'
        );
    }

    // --- Finding 2: Accordion aria-controls ---

    public function test_accordion_header_has_aria_controls(): void
    {
        $this->assertStringContainsString(
            "setAttribute('aria-controls', panelId)",
            $this->quickAiEditJs,
            'AI-1221: accordion header must set aria-controls pointing to panel id'
        );
    }

    public function test_accordion_body_has_id(): void
    {
        $this->assertStringContainsString(
            'bodyEl.id = panelId',
            $this->quickAiEditJs,
            'AI-1221: accordion body panel must have id matching aria-controls'
        );
    }

    public function test_accordion_body_has_role_region(): void
    {
        $this->assertStringContainsString(
            "bodyEl.setAttribute('role', 'region')",
            $this->quickAiEditJs,
            'AI-1221: accordion body panel must have role=region'
        );
    }

    public function test_accordion_body_has_aria_labelledby(): void
    {
        $this->assertStringContainsString(
            "bodyEl.setAttribute('aria-labelledby'",
            $this->quickAiEditJs,
            'AI-1221: accordion body must have aria-labelledby pointing to header'
        );
    }

    public function test_accordion_header_has_id_for_labelledby(): void
    {
        $this->assertMatchesRegularExpression(
            "/headerEl\.id\s*=\s*panelId\s*\+\s*'-header'/",
            $this->quickAiEditJs,
            'AI-1221: accordion header must have id for aria-labelledby reference'
        );
    }

    public function test_panel_id_includes_section_and_type(): void
    {
        $this->assertMatchesRegularExpression(
            "/panelId\s*=\s*'quick-ai-panel-'\s*\+\s*sectionId\s*\+\s*'-'\s*\+\s*typeKey/",
            $this->quickAiEditJs,
            'AI-1221: panel id must be unique per section + type key'
        );
    }

    // --- Finding 3: Submit button touch target ---

    public function test_submit_button_min_height_44px(): void
    {
        $this->assertStringContainsString(
            'min-height: 44px',
            $this->aiChatJs,
            'AI-1221: Submit button must have min-height: 44px for WCAG 2.5.5'
        );
    }

    public function test_submit_button_no_fixed_40px_height(): void
    {
        $stripped = preg_replace('~/\*.*?\*/~s', '', $this->aiChatJs);
        $stripped = preg_replace('~//[^\n]*~', '', $stripped);

        $this->assertDoesNotMatchRegularExpression(
            '/\.mw-ai-chat-box-action-send\s*\{[^}]*\bheight:\s*40px/',
            $stripped,
            'AI-1221: Submit button must not have fixed height: 40px (regression guard)'
        );
    }

    // --- Built bundle verification ---

    public function test_built_bundle_contains_label_for(): void
    {
        $this->assertStringContainsString(
            'for="data-node-id-',
            $this->builtJs,
            'AI-1221: built bundle must contain label for= attribute'
        );
    }

    public function test_built_bundle_contains_aria_controls(): void
    {
        $this->assertStringContainsString(
            'aria-controls',
            $this->builtJs,
            'AI-1221: built bundle must contain aria-controls attribute setting'
        );
    }

    public function test_built_bundle_contains_panel_id_pattern(): void
    {
        $this->assertStringContainsString(
            'quick-ai-panel-',
            $this->builtJs,
            'AI-1221: built bundle must contain the panel id prefix'
        );
    }
}
