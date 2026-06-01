<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-31-b11116 — Save CTA color contract for the Billing module
 * Settings page. AI-816 lineage.
 *
 * Phase 2A Live Edit audit recon surfaced the Billing Settings "Save
 * Settings" action carrying the obsolete ->color('success') shared with
 * the pre-AI-816 family. AI-816 contract: primary-unsubmitted-blue vs
 * post-submit-semantic-success-green. The toolbar SAVE became a BLACK
 * pill in AI-699 / task-2026-05-16-8b10a3, so the "match the green
 * main-toolbar SAVE pill" rationale no longer applies.
 *
 * In-scope (1 change):
 *   - Modules/Billing/Filament/Admin/Pages/Settings.php "Save Settings"
 *
 * Selector-self-match guard per LESSONS UNIFORMITY-RULE: strip PHP
 * line + block comments before the negative regression assertion so a
 * future docblock mentioning the legacy success keyword cannot
 * false-fail the assertion.
 */
class AdminB11116AI816BillingSettingsSaveCtaContractTest extends TestCase
{
    private function fileContents(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    #[Test]
    public function billing_settings_save_cta_renders_brand_blue_not_success_green(): void
    {
        $relativePath = 'Modules/Billing/Filament/Admin/Pages/Settings.php';
        $source = $this->fileContents($relativePath);
        $this->assertNotSame('', $source, "Source file missing: {$relativePath}");

        $anchor = "Action::make('Save Settings')";
        $anchorPos = strpos($source, $anchor);
        $this->assertNotFalse($anchorPos, "Anchor not found in {$relativePath}: {$anchor}");

        // Fixed-length lookahead window per AI-816 lesson.
        $slice = substr($source, $anchorPos, 2000);

        $this->assertStringContainsString(
            "->color('primary')",
            $slice,
            "Expected ->color('primary') in Save Settings action chain"
        );

        // Selector-self-match guard: strip comments before the negative
        // assertion so docblock prose cannot false-fail.
        $sliceWithoutComments = preg_replace('~//[^\n]*~', '', $slice);
        $sliceWithoutComments = preg_replace('~/\*.*?\*/~s', '', (string) $sliceWithoutComments);

        $this->assertStringNotContainsString(
            "->color('success')",
            (string) $sliceWithoutComments,
            'AI-816 regression: Billing Settings Save action still uses ->color(success) post-comment-strip'
        );
    }

    #[Test]
    public function billing_settings_carries_ai816_task_marker(): void
    {
        $relativePath = 'Modules/Billing/Filament/Admin/Pages/Settings.php';
        $source = $this->fileContents($relativePath);

        $this->assertStringContainsString(
            'AI-816',
            $source,
            'Billing Settings file must carry the AI-816 task marker so future audits can locate the fix lineage'
        );
        $this->assertStringContainsString(
            'task-2026-05-31',
            $source,
            'Billing Settings file must carry the task-2026-05-31 marker for grep-ability across surfaces'
        );
    }
}
