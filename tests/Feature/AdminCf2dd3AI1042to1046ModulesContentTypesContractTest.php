<?php

use Tests\TestCase;

/**
 * Contract test — AI-1042 / AI-1043 / AI-1044 / AI-1045 / AI-1046
 * task-2026-05-23-cf2dd3 / task-2026-05-23-bbad8f / task-2026-05-23-78686e
 * task-2026-05-23-d1aa85 / task-2026-05-23-82ffcf
 *
 * AI-1042: Module card description column (2-line clamp from module.json).
 * AI-1043: Empty state uses puzzle-piece icon + Browse Marketplace CTA.
 * AI-1044: Content Types subtitle uses user-facing copy, not developer language.
 * AI-1045: AI-732 Jira reference removed from Content Types blade template.
 * AI-1046: Version badge shows v{version} from module.json; hidden when version = 'dev'.
 *
 * Selector-self-match guard: PHP block comments stripped before assertions.
 */
class AdminCf2dd3AI1042to1046ModulesContentTypesContractTest extends TestCase
{
    private string $moduleResourceSrc;
    private string $moduleResourceExec;
    private string $contentTypesPageSrc;
    private string $contentTypesPageExec;
    private string $contentTypesBladeSrc;
    private string $contentTypesBladeExec;

    protected function setUp(): void
    {
        parent::setUp();

        $mr = (string) file_get_contents(
            base_path('src/MicroweberPackages/LaravelModules/Filament/Resources/ModuleResource/ModuleResource.php')
        );
        $this->moduleResourceSrc = $mr;
        $s = preg_replace('~/\*[\s\S]*?\*/~s', '', $mr);
        $s = preg_replace('~//[^\n]*~', '', $s);
        $this->moduleResourceExec = $s;

        $ct = (string) file_get_contents(
            base_path('Modules/Content/Filament/Admin/Pages/ContentTypesPage.php')
        );
        $this->contentTypesPageSrc = $ct;
        $cs = preg_replace('~/\*[\s\S]*?\*/~s', '', $ct);
        $cs = preg_replace('~//[^\n]*~', '', $cs);
        $this->contentTypesPageExec = $cs;

        $blade = (string) file_get_contents(
            base_path('Modules/Content/resources/views/filament/admin/pages/content-types-page.blade.php')
        );
        $this->contentTypesBladeSrc = $blade;
        $bs = preg_replace('~\{\{--[\s\S]*?--\}\}~s', '', $blade);
        $this->contentTypesBladeExec = $bs;
    }

    // ── Group A: AI-1042 — module description column ──────────────────────────

    public function test_description_column_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~TextColumn::make\(\'description\'\)~',
            $this->moduleResourceExec,
            "AI-1042: a TextColumn for 'description' must be added to the modules table"
        );
    }

    public function test_description_column_has_line_clamp_css(): void
    {
        $this->assertStringContainsString(
            '-webkit-line-clamp:2',
            $this->moduleResourceExec,
            'AI-1042: description column must use -webkit-line-clamp:2 for 2-line truncation'
        );
    }

    public function test_description_column_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-23-cf2dd3', $this->moduleResourceSrc,
            'AI-1042 task-id marker must be in ModuleResource');
    }

    // ── Group B: AI-1043 — empty state puzzle icon ────────────────────────────

    public function test_empty_state_uses_puzzle_piece_icon(): void
    {
        $this->assertStringContainsString(
            "emptyStateIcon('heroicon-o-puzzle-piece')",
            $this->moduleResourceExec,
            'AI-1043: empty state must use heroicon-o-puzzle-piece (not circle-X)'
        );
    }

    public function test_empty_state_has_browse_marketplace_action(): void
    {
        $this->assertStringContainsString(
            'browse_marketplace',
            $this->moduleResourceExec,
            'AI-1043: empty state must include a Browse Marketplace action'
        );
        $this->assertStringContainsString(
            '/admin/marketplace',
            $this->moduleResourceExec,
            'AI-1043: Browse Marketplace CTA must link to /admin/marketplace'
        );
    }

    public function test_empty_state_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-23-bbad8f', $this->moduleResourceSrc,
            'AI-1043 task-id marker must be in ModuleResource');
    }

    // ── Group C: AI-1044 — Content Types subtitle ─────────────────────────────

    public function test_subtitle_uses_user_facing_copy(): void
    {
        $this->assertStringContainsString(
            "Manage your site's content structure",
            $this->contentTypesPageExec,
            'AI-1044: subtitle must use user-facing copy'
        );
    }

    public function test_subtitle_does_not_contain_developer_language(): void
    {
        $this->assertStringNotContainsString(
            'Distinct content_type values stored in the content table',
            $this->contentTypesPageExec,
            'AI-1044: developer database language must be removed from subtitle'
        );
    }

    public function test_subtitle_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-23-78686e', $this->contentTypesPageSrc,
            'AI-1044 task-id marker must be in ContentTypesPage');
    }

    // ── Group D: AI-1045 — AI-732 Jira reference removed ─────────────────────

    public function test_ai732_reference_removed_from_blade(): void
    {
        $this->assertStringNotContainsString(
            'AI-732 ships read-only listing',
            $this->contentTypesBladeExec,
            'AI-1045: internal AI-732 Jira reference must be removed from production blade output'
        );
    }

    public function test_blade_task_marker_for_ai1045(): void
    {
        $this->assertStringContainsString('task-2026-05-23-d1aa85', $this->contentTypesBladeSrc,
            'AI-1045 task-id marker must be in content-types-page.blade.php');
    }

    // ── Group E: AI-1046 — version badge ─────────────────────────────────────

    public function test_version_column_present(): void
    {
        $this->assertMatchesRegularExpression(
            "~TextColumn::make\('version'\)~",
            $this->moduleResourceExec,
            "AI-1046: a TextColumn for 'version' must be added to the modules table"
        );
    }

    public function test_version_badge_omits_dev_placeholder(): void
    {
        // The formatStateUsing must check for 'dev' and return empty string.
        $this->assertStringContainsString(
            "'dev'",
            $this->moduleResourceExec,
            "AI-1046: version formatStateUsing must exclude the 'dev' placeholder"
        );
    }

    public function test_version_badge_prefixes_v(): void
    {
        // The formatted output must prepend 'v' to the version string.
        $this->assertStringContainsString(
            "'v'",
            $this->moduleResourceExec,
            "AI-1046: formatted version must prepend 'v' (e.g. v1.2.0)"
        );
    }

    public function test_version_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-23-82ffcf', $this->moduleResourceSrc,
            'AI-1046 task-id marker must be in ModuleResource');
    }
}
