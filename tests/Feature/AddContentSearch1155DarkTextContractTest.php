<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1155 / task-2026-05-28-2f5a6c Batch 2 #1.
 *
 * Scope (dispatch verbatim): "Add Content modal search input dark mode
 * text color. Fix: add dark:text-gray-200 to search input."
 *
 * Defect: the search input under .mw-add-content-modal-search carried
 * dark:bg-gray-900 (near-black background in dark mode) but no
 * dark:text-* utility — the typed text inherited the default foreground
 * (near-black) and washed to invisible against the dark background.
 *
 * Fix: add dark:text-gray-200 to the same class string. Contrast
 * gray-200 (#e5e7eb) on gray-900 (#111827) ≈ 14:1 — well above the
 * WCAG AA 4.5:1 threshold for non-large text.
 */
class AddContentSearch1155DarkTextContractTest extends TestCase
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

    public function test_search_input_carries_dark_text_class(): void
    {
        $this->assertMatchesRegularExpression(
            '/class="mw-add-content-modal-search-input[^"]*\bdark:text-gray-200\b/',
            $this->blade,
            'AI-1155: .mw-add-content-modal-search-input must carry dark:text-gray-200'
        );
    }

    public function test_search_input_keeps_dark_background(): void
    {
        $this->assertMatchesRegularExpression(
            '/class="mw-add-content-modal-search-input[^"]*\bdark:bg-gray-900\b/',
            $this->blade,
            'AI-1155: dark:bg-gray-900 must remain on the search input — the dark text class needs the dark bg to be readable.'
        );
    }

    public function test_search_input_keeps_light_text_floor(): void
    {
        $this->assertMatchesRegularExpression(
            '/class="mw-add-content-modal-search-input[^"]*\btext-gray-900\b/',
            $this->blade,
            'AI-1155: light-mode text-gray-900 must accompany the dark:text-gray-200 — fix must not regress the light-mode contrast.'
        );
    }

    public function test_ai_1155_task_id_marker_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-2f5a6c / AI-1155',
            $this->blade,
            'AI-1155: task-id marker comment must be present adjacent to the fix'
        );
    }
}
