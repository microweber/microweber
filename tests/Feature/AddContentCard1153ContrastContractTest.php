<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1153 / task-2026-05-28-2f5a6c Batch 2 #2.
 *
 * Scope (dispatch verbatim): "Add Content card title/subtitle contrast
 * ~4.1:1 below WCAG AA. Fix: increase to ≥4.5:1."
 *
 * Three surfaces:
 *
 *  1) The "New content" section subtitle previously used
 *     text-gray-400 dark:text-gray-300. In light mode text-gray-400
 *     (#9ca3af) on the white modal background measures ~2.85:1 — well
 *     below the WCAG AA 4.5:1 floor. Bumped to text-gray-600 (#4b5563
 *     on white ≈ 7.56:1).
 *
 *  2) The primary group card title previously had no explicit colour,
 *     inheriting button-default which measured ~4.1:1 in dark mode
 *     against the Filament modal surface. Pinned to text-gray-900
 *     dark:text-gray-100.
 *
 *  3) The secondary group card title — same defect, same fix.
 */
class AddContentCard1153ContrastContractTest extends TestCase
{
    private string $blade;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->blade = (string) file_get_contents(
            self::projectRoot()
            . '/src/MicroweberPackages/LiveEdit/resources/views/add-content-modal.blade.php'
        );
    }

    public function test_new_content_subtitle_uses_text_gray_600(): void
    {
        $this->assertMatchesRegularExpression(
            '/<p class="text-xs text-gray-600 dark:text-gray-300 [^"]*">Create something new for your website<\/p>/',
            $this->blade,
            'AI-1153: "Create something new for your website" subtitle must use text-gray-600 (light) + dark:text-gray-300 (dark) for ≥4.5:1 contrast'
        );
    }

    public function test_new_content_subtitle_no_longer_uses_text_gray_400(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/<p class="text-xs text-gray-400[^"]*">Create something new for your website<\/p>/',
            $this->blade,
            'AI-1153: legacy text-gray-400 (~2.85:1) on the new-content subtitle must be gone'
        );
    }

    public function test_primary_card_title_has_explicit_dark_text_color(): void
    {
        $this->assertMatchesRegularExpression(
            '/<div class="font-semibold text-base leading-tight text-gray-900 dark:text-gray-100">/',
            $this->blade,
            'AI-1153: primary card title must carry explicit text-gray-900 dark:text-gray-100 (no inheritance)'
        );
    }

    public function test_secondary_card_title_has_explicit_dark_text_color(): void
    {
        $this->assertMatchesRegularExpression(
            '/<div class="font-semibold text-sm leading-tight text-gray-900 dark:text-gray-100">/',
            $this->blade,
            'AI-1153: secondary card title must carry explicit text-gray-900 dark:text-gray-100 (no inheritance)'
        );
    }

    public function test_no_card_title_without_explicit_color_remains(): void
    {
        // Negative regression guard: the bare class strings (without an
        // explicit text-gray-* utility) must not regress back.
        $this->assertDoesNotMatchRegularExpression(
            '/<div class="font-semibold text-base leading-tight">\s*\{\{ \$action\[\'title\'\] \}\}/',
            $this->blade,
            'AI-1153: primary card title must not regress to inheritance-only colour'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/<div class="font-semibold text-sm leading-tight">\s*\{\{ \$action\[\'title\'\] \}\}/',
            $this->blade,
            'AI-1153: secondary card title must not regress to inheritance-only colour'
        );
    }

    public function test_ai_1153_task_id_marker_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-2f5a6c / AI-1153',
            $this->blade,
            'AI-1153: task-id marker comment must be present adjacent to the fix'
        );
    }
}
