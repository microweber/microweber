<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-31-a12031 — AI-817 follow-up. sr-only label-restoration for
 * the Category MwTree parent picker (admin-panel form surface outside the
 * Live Edit canvas).
 *
 * AI-817 originally shipped the sr-only label-restoration pattern for
 * Live-Edit canvas surfaces (ContentResource compactTitleOnlySection,
 * AdminLiveEditPage addImageAction). The canonical .mw-fb-title-wrap
 * CSS rule lived ONLY in live-edit-module-settings.blade.php and
 * iframe-page.blade.php — admin-panel forms outside Live Edit could not
 * consume the pattern because the rule was not loaded.
 *
 * This follow-up:
 *   1. Mirrors the canonical sr-only rule into the admin panel global
 *      stylesheet (general-styles.css tail) so any admin-panel form
 *      surface tagging fields with .mw-fb-title-wrap resolves the rule.
 *   2. Replaces ->hiddenLabel() with ->extraFieldWrapperAttributes(['class'
 *      => 'mw-tree-wrapper mw-fb-title-wrap']) on Category MwTree, keeping
 *      the inner "Choose Parent Page or Category" label in the AT.
 *
 * Newsletter CreateCampaign is intentionally OUT OF SCOPE here: that page
 * renders a custom blade view (create-campaign.blade.php) that bypasses
 * the Filament form() schema entirely. The rendered surface already
 * carries a raw HTML label.sr-only + aria-label="Campaign name", so AT
 * compliance is preserved at the rendered surface without any form()
 * schema change. Verified via Playwright probe during ship.
 *
 * Selector-self-match guard per LESSONS UNIFORMITY-RULE: strip PHP line +
 * block + Blade comments + CSS comments before negative regression
 * assertions so docblock prose mentioning ->hiddenLabel() cannot
 * false-fail the assertion.
 */
class AdminA12031AI817CategorySrOnlyLabelContractTest extends TestCase
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

    private function stripCssComments(string $source): string
    {
        return (string) preg_replace('~/\*.*?\*/~s', '', $source);
    }

    #[Test]
    public function general_styles_css_carries_mw_fb_title_wrap_sr_only_rule(): void
    {
        $relativePath = 'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css';
        $source = $this->fileContents($relativePath);
        $this->assertNotSame('', $source, "Source file missing: {$relativePath}");

        $sourceWithoutComments = $this->stripCssComments($source);

        $this->assertMatchesRegularExpression(
            '/\.mw-fb-title-wrap[^{]*(label|fi-fo-field-lbl)/',
            $sourceWithoutComments,
            'general-styles.css must carry an sr-only CSS rule scoped under .mw-fb-title-wrap targeting label or .fi-fo-field-lbl'
        );

        $this->assertStringContainsString(
            'position: absolute',
            $sourceWithoutComments,
            'sr-only rule requires position: absolute'
        );
        $this->assertStringContainsString(
            'clip: rect(0',
            $sourceWithoutComments,
            'sr-only rule requires clip: rect(0,0,0,0)'
        );
    }

    public static function srOnlyConsumerProvider(): array
    {
        return [
            'Category MwTree parent picker' => [
                'Modules/Category/Filament/Admin/Resources/CategoryResource.php',
                "MwTree::make('mw_parent_page_and_category_state')",
                "->label('Choose Parent Page or Category')",
                "'class' => 'mw-tree-wrapper mw-fb-title-wrap'",
            ],
        ];
    }

    #[Test]
    #[DataProvider('srOnlyConsumerProvider')]
    public function consumer_surfaces_carry_label_and_sr_only_wrapper(
        string $relativePath,
        string $anchor,
        string $expectedLabel,
        string $expectedWrapperClassSnippet
    ): void {
        $source = $this->fileContents($relativePath);
        $this->assertNotSame('', $source, "Source file missing: {$relativePath}");

        $anchorPos = strpos($source, $anchor);
        $this->assertNotFalse($anchorPos, "Anchor not found in {$relativePath}: {$anchor}");

        $slice = substr($source, $anchorPos, 2000);

        $this->assertStringContainsString(
            $expectedLabel,
            $slice,
            "{$relativePath} must carry explicit {$expectedLabel} for AT announcement"
        );

        $this->assertStringContainsString(
            $expectedWrapperClassSnippet,
            $slice,
            "{$relativePath} must apply the mw-fb-title-wrap sr-only wrapper class"
        );
    }

    #[Test]
    #[DataProvider('srOnlyConsumerProvider')]
    public function consumer_surfaces_do_not_use_hidden_label(
        string $relativePath,
        string $anchor,
        string $expectedLabel,
        string $expectedWrapperClassSnippet
    ): void {
        $source = $this->fileContents($relativePath);

        $anchorPos = strpos($source, $anchor);
        $this->assertNotFalse($anchorPos);

        $slice = substr($source, $anchorPos, 2000);
        $sliceWithoutComments = $this->stripPhpComments($slice);

        $this->assertStringNotContainsString(
            '->hiddenLabel(',
            $sliceWithoutComments,
            "AI-817 regression in {$relativePath}: {$anchor} still uses hiddenLabel() which strips the label from the accessibility tree (WCAG 3.3.2 Level A failure)"
        );
    }

    #[Test]
    public function task_markers_present_in_consumer_surfaces(): void
    {
        $surfaces = [
            'Modules/Category/Filament/Admin/Resources/CategoryResource.php',
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css',
        ];

        foreach ($surfaces as $relativePath) {
            $source = $this->fileContents($relativePath);
            $this->assertStringContainsString(
                'AI-817',
                $source,
                "{$relativePath} must carry the AI-817 task marker so future audits can locate the fix lineage"
            );
            $this->assertStringContainsString(
                'task-2026-05-31',
                $source,
                "{$relativePath} must carry the task-2026-05-31 marker for grep-ability across surfaces"
            );
        }
    }
}
