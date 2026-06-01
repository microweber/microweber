<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-31-a11817 — sr-only label-restoration for the Live Edit
 * Add Image action's FileUpload component. AI-817 lineage.
 *
 * Phase 2A Live Edit audit recon surfaced AdminLiveEditPage's
 * addImageAction FileUpload carrying ->hiddenLabel() which strips the
 * label from the accessibility tree entirely (WCAG 3.3.2 Level A
 * failure). AI-817 contract: replace ->hiddenLabel() with ->label('...')
 * + ->extraFieldWrapperAttributes(['class' => 'mw-fb-title-wrap']) so
 * the label is visually hidden but read by screen readers via the
 * canonical sr-only positioning rule.
 *
 * In-scope (2 changes):
 *   1. AdminLiveEditPage::addImageAction FileUpload chain
 *   2. iframe-page.blade.php sr-only CSS rule mirror
 *
 * The canonical sr-only rule lives in live-edit-module-settings
 * blade view; the mirror in iframe-page blade ensures the rule
 * resolves inside the canvas iframe too.
 *
 * Selector-self-match guard per LESSONS UNIFORMITY-RULE: strip PHP
 * line + block + Blade comments before the negative regression
 * assertion so docblock prose mentioning ->hiddenLabel() cannot
 * false-fail the assertion.
 */
class AdminA11817AI817AddImageActionSrOnlyLabelContractTest extends TestCase
{
    private function fileContents(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    private function stripPhpComments(string $source): string
    {
        $stripped = preg_replace('~/\*.*?\*/~s', '', $source);
        $stripped = preg_replace('~//[^\n]*~', '', (string) $stripped);

        return (string) $stripped;
    }

    private function stripBladeComments(string $source): string
    {
        return (string) preg_replace('~\{\{--.*?--\}\}~s', '', $source);
    }

    #[Test]
    public function add_image_file_upload_uses_label_and_sr_only_wrapper(): void
    {
        $relativePath = 'src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php';
        $source = $this->fileContents($relativePath);
        $this->assertNotSame('', $source, "Source file missing: {$relativePath}");

        $anchor = "FileUpload::make('images')";
        $anchorPos = strpos($source, $anchor);
        $this->assertNotFalse($anchorPos, "Anchor not found: {$anchor}");

        $slice = substr($source, $anchorPos, 2000);

        $this->assertStringContainsString(
            "->label('Image files')",
            $slice,
            "FileUpload must carry an explicit ->label('Image files') so the accessibility tree announces the input purpose"
        );

        $this->assertStringContainsString(
            "->extraFieldWrapperAttributes(['class' => 'mw-fb-title-wrap'])",
            $slice,
            "FileUpload must apply the mw-fb-title-wrap sr-only wrapper class"
        );
    }

    #[Test]
    public function add_image_file_upload_does_not_use_hidden_label(): void
    {
        $relativePath = 'src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php';
        $source = $this->fileContents($relativePath);

        $anchor = "FileUpload::make('images')";
        $anchorPos = strpos($source, $anchor);
        $this->assertNotFalse($anchorPos);

        $slice = substr($source, $anchorPos, 2000);
        $sliceWithoutComments = $this->stripPhpComments($slice);

        $this->assertStringNotContainsString(
            '->hiddenLabel(',
            $sliceWithoutComments,
            'AI-817 regression: addImageAction FileUpload still uses ->hiddenLabel() which strips the label from the accessibility tree (WCAG 3.3.2 Level A failure)'
        );
    }

    #[Test]
    public function iframe_page_blade_carries_sr_only_rule_for_mw_fb_title_wrap(): void
    {
        $relativePath = 'src/MicroweberPackages/LiveEdit/resources/views/iframe-page.blade.php';
        $source = $this->fileContents($relativePath);
        $this->assertNotSame('', $source, "Source file missing: {$relativePath}");

        $sourceWithoutComments = $this->stripBladeComments($source);

        // The sr-only rule must target at least one of the canonical
        // Filament label selectors under the mw-fb-title-wrap scope.
        $this->assertMatchesRegularExpression(
            '/\.mw-fb-title-wrap[^{]*(label|fi-fo-field-lbl)/',
            $sourceWithoutComments,
            'iframe-page blade must carry an sr-only CSS rule scoped under .mw-fb-title-wrap targeting label or .fi-fo-field-lbl'
        );

        // Core sr-only properties must be present.
        $this->assertStringContainsString(
            'position: absolute',
            $sourceWithoutComments,
            'sr-only rule requires position: absolute (canonical accessible-hide pattern)'
        );
        $this->assertStringContainsString(
            'clip: rect(0',
            $sourceWithoutComments,
            'sr-only rule requires clip: rect(0,0,0,0) so visual extent collapses while staying in the accessibility tree'
        );
    }

    #[Test]
    public function admin_live_edit_page_carries_ai817_task_marker(): void
    {
        $relativePath = 'src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php';
        $source = $this->fileContents($relativePath);

        $this->assertStringContainsString(
            'AI-817',
            $source,
            'AdminLiveEditPage must carry the AI-817 task marker so future audits can locate the fix lineage'
        );
    }
}
