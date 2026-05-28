<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1145 / task-2026-05-28-2f5a6c Batch 2 #3.
 *
 * Scope (dispatch verbatim): "Insert Layout modal padding/spacing/grid
 * inconsistencies. Fix: standardise gutter + outer padding to a single
 * 16px value across masonry + list + full views."
 *
 * Two source files own the spacing across the three views:
 *
 *  1) packages/frontend-assets/resources/assets/ui/components/Layouts/
 *     ListLayouts.vue — the masonry view mounts <MasonryWall> with
 *     :gap + :padding props. Pre-fix both were 12 (px); post-fix both
 *     are 16 (px).
 *
 *  2) packages/frontend-assets/resources/assets/ui/css/index.css —
 *     the list + full views render through containerClasses
 *     'modules-list-block', whose .mw-le-layouts-dialog .modules-list-block
 *     rule sets padding + gap. Pre-fix both were 30px; post-fix both
 *     are 16px (Tailwind gap-4 = the design-system standard gutter).
 *
 * Both files carry an inline task-id marker comment per the per-task
 * source-comment marker convention (commit + contract test + source).
 */
class InsertLayout1145SpacingContractTest extends TestCase
{
    private string $vue;

    private string $css;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->vue = (string) file_get_contents(
            self::projectRoot()
            . '/packages/frontend-assets/resources/assets/ui/components/Layouts/ListLayouts.vue'
        );

        $this->css = (string) file_get_contents(
            self::projectRoot()
            . '/packages/frontend-assets/resources/assets/ui/css/index.css'
        );
    }

    public function test_masonry_wall_uses_gap_16(): void
    {
        $this->assertMatchesRegularExpression(
            '/<MasonryWall\b[^>]*?:gap="16"/s',
            $this->vue,
            'AI-1145: <MasonryWall> must use :gap="16" (was :gap="12") for a 16px gutter consistent with list + full views'
        );
    }

    public function test_masonry_wall_uses_padding_16(): void
    {
        $this->assertMatchesRegularExpression(
            '/<MasonryWall\b[^>]*?:padding="16"/s',
            $this->vue,
            'AI-1145: <MasonryWall> must use :padding="16" (was :padding="12") for a 16px outer padding consistent with list + full views'
        );
    }

    public function test_masonry_wall_no_longer_uses_gap_12(): void
    {
        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->vue);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);
        $stripped = (string) preg_replace('~<!--.*?-->~s', '', $stripped);

        $this->assertDoesNotMatchRegularExpression(
            '/<MasonryWall\b[^>]*?:gap="12"/s',
            $stripped,
            'AI-1145: legacy <MasonryWall> :gap="12" must be gone (comment-strip selector-self-match guard)'
        );
    }

    public function test_masonry_wall_no_longer_uses_padding_12(): void
    {
        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->vue);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);
        $stripped = (string) preg_replace('~<!--.*?-->~s', '', $stripped);

        $this->assertDoesNotMatchRegularExpression(
            '/<MasonryWall\b[^>]*?:padding="12"/s',
            $stripped,
            'AI-1145: legacy <MasonryWall> :padding="12" must be gone (comment-strip selector-self-match guard)'
        );
    }

    public function test_modules_list_block_uses_padding_16(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-dialog\s+\.modules-list-block\s*\{[^}]*?padding:\s*16px;/s',
            $this->css,
            'AI-1145: .mw-le-layouts-dialog .modules-list-block must use padding: 16px (was 30px) for list + full view outer padding'
        );
    }

    public function test_modules_list_block_uses_gap_16(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-le-layouts-dialog\s+\.modules-list-block\s*\{[^}]*?gap:\s*16px;/s',
            $this->css,
            'AI-1145: .mw-le-layouts-dialog .modules-list-block must use gap: 16px (was 30px) for list + full view item gutter'
        );
    }

    public function test_modules_list_block_no_longer_uses_padding_30px(): void
    {
        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->css);

        $this->assertDoesNotMatchRegularExpression(
            '/\.mw-le-layouts-dialog\s+\.modules-list-block\s*\{[^}]*?padding:\s*30px;/s',
            $stripped,
            'AI-1145: legacy padding: 30px on .modules-list-block must be gone (comment-strip selector-self-match guard)'
        );
    }

    public function test_modules_list_block_no_longer_uses_gap_30px(): void
    {
        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->css);

        $this->assertDoesNotMatchRegularExpression(
            '/\.mw-le-layouts-dialog\s+\.modules-list-block\s*\{[^}]*?gap:\s*30px;/s',
            $stripped,
            'AI-1145: legacy gap: 30px on .modules-list-block must be gone (comment-strip selector-self-match guard)'
        );
    }

    public function test_ai_1145_task_id_marker_in_vue_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-2f5a6c / AI-1145',
            $this->vue,
            'AI-1145: task-id marker comment must be present adjacent to the masonry MasonryWall fix in ListLayouts.vue'
        );
    }

    public function test_ai_1145_task_id_marker_in_css_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-2f5a6c / AI-1145',
            $this->css,
            'AI-1145: task-id marker comment must be present adjacent to the .modules-list-block fix in index.css'
        );
    }
}
