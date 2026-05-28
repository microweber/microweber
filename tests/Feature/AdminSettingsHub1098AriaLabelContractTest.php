<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1098 / task-2026-05-28-2f5a6c Batch 2.
 *
 * Scope (dispatch verbatim): "Sidebar Module Dependencies link
 * accessible-name whitespace leak (Blade trim fix)."
 *
 * Failure family pinned by this test:
 *
 *  The settings-hub Blade renders each settings item as an anchor
 *  wrapping multi-line content — icon SVG + title h3 + description
 *  p. AT computes the link's accessible name from text content of
 *  the anchor, which includes the indent whitespace between those
 *  inner elements. The visible label "Module Dependencies" is
 *  clean (no whitespace) but the computed accessible name carried
 *  significant whitespace because the indented Blade markup
 *  contributed it.
 *
 *  Fix: explicit aria-label="{{ trim($setting['title']) }}" on the
 *  <a class="mw-settings-hub-item"> anchor so AT consumes the
 *  trimmed title as the accessible name, bypassing the whitespace
 *  leak from the multi-line content.
 *
 *  Per Slice B audit-the-base-class discipline: the fix applies to
 *  ALL settings hub items (one shared Blade template), not just
 *  Module Dependencies. The dispatch named Module Dependencies as
 *  the witness symptom; the fix lands at the single shared anchor.
 *
 * The source file carries the task-id marker comment per the
 * per-task source-comment marker convention.
 */
class AdminSettingsHub1098AriaLabelContractTest extends TestCase
{
    private string $settingsHub;

    private string $settingsHubStripped;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->settingsHub = (string) file_get_contents(
            self::projectRoot()
            . '/Modules/Settings/resources/views/filament/admin/pages/settings-main.blade.php'
        );

        $stripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $this->settingsHub);
        $stripped = (string) preg_replace('~<!--.*?-->~s', '', $stripped);
        $this->settingsHubStripped = $stripped;
    }

    public function test_settings_hub_anchor_carries_aria_label_with_trim(): void
    {
        $this->assertMatchesRegularExpression(
            '/<a\s+href="\{\{\s*\$setting\[.url.\]\s*\}\}"\s+class="mw-settings-hub-item"\s+aria-label="\{\{\s*trim\(\s*\$setting\[.title.\]\s*\)\s*\}\}"\s*>/',
            $this->settingsHub,
            'AI-1098: <a class="mw-settings-hub-item"> must carry aria-label="{{ trim($setting[\'title\']) }}" so the accessible name is the cleanly-trimmed title rather than the multi-line content whitespace leak'
        );
    }

    public function test_settings_hub_no_bare_anchor_without_aria_label(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/<a\s+href="\{\{\s*\$setting\[.url.\]\s*\}\}"\s+class="mw-settings-hub-item"\s*>/',
            $this->settingsHubStripped,
            'AI-1098: legacy bare <a class="mw-settings-hub-item"> (no aria-label) must be gone from settings-main.blade.php'
        );
    }

    public function test_settings_hub_preserves_multiline_content_structure(): void
    {
        $this->assertStringContainsString(
            'mw-settings-hub-item-icon',
            $this->settingsHub,
            'AI-1098: inner icon wrapper preserved verbatim — the AI-1098 fix only adds aria-label to the outer anchor'
        );

        $this->assertStringContainsString(
            'mw-settings-hub-item-title',
            $this->settingsHub,
            'AI-1098: inner title h3 preserved verbatim'
        );

        $this->assertStringContainsString(
            'mw-settings-hub-item-description',
            $this->settingsHub,
            'AI-1098: inner description p preserved verbatim'
        );
    }

    public function test_ai_1098_task_id_marker_in_settings_hub(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-2f5a6c / AI-1098',
            $this->settingsHub,
            'AI-1098: task-id marker comment must be present adjacent to the aria-label change in settings-main.blade.php'
        );
    }
}
