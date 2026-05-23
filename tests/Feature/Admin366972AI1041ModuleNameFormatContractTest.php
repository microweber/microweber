<?php

use Tests\TestCase;

/**
 * Contract test — AI-1041 / task-2026-05-23-366972
 *
 * Modules page showed raw PascalCase names like 'LayoutContent', 'AiWizard'.
 * Fix: pipe the name TextColumn through AdminDisplayName::format() which
 * space-splits camelCase and uppercases known acronyms (AI, SEO, etc.).
 * Mirrors the existing pattern in MarketplaceResource.
 *
 * Selector-self-match guard: PHP block comments stripped before assertions.
 */
class Admin366972AI1041ModuleNameFormatContractTest extends TestCase
{
    private string $src;
    private string $executable;

    protected function setUp(): void
    {
        parent::setUp();

        $raw = (string) file_get_contents(
            base_path('src/MicroweberPackages/LaravelModules/Filament/Resources/ModuleResource/ModuleResource.php')
        );
        $this->src = $raw;
        $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $raw);
        $stripped = preg_replace('~//[^\n]*~', '', $stripped);
        $this->executable = $stripped;
    }

    // ── Group A: AdminDisplayName applied to name column ─────────────────────

    public function test_adminDisplayName_import_present(): void
    {
        $this->assertStringContainsString(
            'use MicroweberPackages\Filament\Support\AdminDisplayName;',
            $this->src,
            'ModuleResource must import AdminDisplayName'
        );
    }

    public function test_name_column_has_formatStateUsing_with_adminDisplayName(): void
    {
        $this->assertMatchesRegularExpression(
            '~->formatStateUsing\s*\(\s*fn\s*\(\?string\s*\$state\)\s*:\s*string\s*=>\s*AdminDisplayName::format\(\$state\)\s*\)~',
            $this->executable,
            'name TextColumn must use formatStateUsing(fn(?string $state): string => AdminDisplayName::format($state))'
        );
    }

    public function test_task_id_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-23-366972',
            $this->src,
            'ModuleResource must carry AI-1041 task-id marker'
        );
    }

    // ── Group B: AdminDisplayName produces expected output for sample names ───

    public function test_layout_content_formatted(): void
    {
        $result = \MicroweberPackages\Filament\Support\AdminDisplayName::format('LayoutContent');
        $this->assertStringContainsString('Layout', $result,
            'LayoutContent must be split into words containing "Layout"');
        $this->assertStringContainsString('content', $result,
            'LayoutContent must be split into words containing "content"');
        $this->assertStringNotContainsString('LayoutContent', $result,
            'Raw PascalCase "LayoutContent" must not appear in formatted output');
    }

    public function test_ai_wizard_formatted(): void
    {
        $result = \MicroweberPackages\Filament\Support\AdminDisplayName::format('AiWizard');
        $this->assertStringContainsString('AI', $result,
            '"AI" acronym must be uppercased in formatted output for AiWizard');
        $this->assertStringContainsString('wizard', $result,
            '"wizard" word must appear in formatted output for AiWizard');
        $this->assertStringNotContainsString('AiWizard', $result,
            'Raw PascalCase "AiWizard" must not appear in formatted output');
    }

    public function test_custom_fields_formatted(): void
    {
        $result = \MicroweberPackages\Filament\Support\AdminDisplayName::format('CustomFields');
        $this->assertStringContainsString('Custom', $result);
        $this->assertStringNotContainsString('CustomFields', $result);
    }
}
