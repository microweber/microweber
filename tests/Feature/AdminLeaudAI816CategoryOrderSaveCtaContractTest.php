<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-leaud — Save CTA color contract extension to
 * Category + Order resources. AI-816 lineage.
 *
 * Phase 2A Live Edit audit recon (Slice B audit-the-base-class)
 * surfaced 4 sibling unsubmitted Save CTAs sharing the obsolete
 * "match the green main-toolbar SAVE pill" rationale flagged by
 * AI-816. The toolbar SAVE became a BLACK pill in AI-699 /
 * task-2026-05-16-8b10a3, so the green-match argument no longer
 * applies on any of these surfaces.
 *
 * In-scope (4 changes):
 *   1. CategoryResource/Pages/CreateCategory.php (saveCategory action)
 *   2. CategoryResource/Pages/EditCategory.php   (saveCategory action)
 *   3. OrderResource/Pages/CreateOrder.php       (save action)
 *   4. OrderResource/Pages/EditOrder.php         (EditAction Save)
 *
 * Out-of-scope, intentionally stays green (post-submit semantic
 * success per AI-816):
 *   - CommentResource approve action (transitions to approved state)
 *   - ProductPricingRuleResource Activate bulk action (Publish-precedent)
 *   - WorkflowResource success_count TextColumn (column display)
 *   - Publish bulk action / Mark-Paid / Install / Place Order
 *     (all pinned by Admin2b1020AI816SaveCtaPrimaryColorContractTest)
 */
class AdminLeaudAI816CategoryOrderSaveCtaContractTest extends TestCase
{
    private function fileContents(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    public static function saveCtaProvider(): array
    {
        return [
            'CreateCategory saveCategory action' => [
                'Modules/Category/Filament/Admin/Resources/CategoryResource/Pages/CreateCategory.php',
                "Actions\\Action::make('saveCategory')",
            ],
            'EditCategory saveCategory action' => [
                'Modules/Category/Filament/Admin/Resources/CategoryResource/Pages/EditCategory.php',
                "Actions\\Action::make('saveCategory')",
            ],
            'CreateOrder save action' => [
                'Modules/Order/Filament/Admin/Resources/OrderResource/Pages/CreateOrder.php',
                "Actions\\Action::make('save')",
            ],
            'EditOrder EditAction Save' => [
                'Modules/Order/Filament/Admin/Resources/OrderResource/Pages/EditOrder.php',
                'Actions\\EditAction::make()',
            ],
        ];
    }

    #[Test]
    #[DataProvider('saveCtaProvider')]
    public function save_cta_renders_brand_blue_not_success_green(string $relativePath, string $anchor): void
    {
        $source = $this->fileContents($relativePath);
        $this->assertNotSame('', $source, "Source file missing: {$relativePath}");

        $anchorPos = strpos($source, $anchor);
        $this->assertNotFalse($anchorPos, "Anchor not found in {$relativePath}: {$anchor}");

        // Fixed-length lookahead window (AI-816 lesson). Bounding on
        // the next `;` is unsafe — docblock prose can contain `;`.
        $slice = substr($source, $anchorPos, 2000);

        $this->assertStringContainsString(
            "->color('primary')",
            $slice,
            "Expected ->color('primary') in action chain for {$anchor} in {$relativePath}"
        );

        // Selector-self-match guard: pre-strip PHP `//` line + `/* */`
        // block comments before the negative assertion so commented-out
        // legacy `->color('success')` references don't false-fail.
        $sliceWithoutComments = preg_replace('~//[^\n]*~', '', $slice);
        $sliceWithoutComments = preg_replace('~/\*.*?\*/~s', '', (string) $sliceWithoutComments);

        $this->assertStringNotContainsString(
            "->color('success')",
            (string) $sliceWithoutComments,
            "AI-816 regression: action chain for {$anchor} in {$relativePath} still uses ->color('success')"
        );
    }

    #[Test]
    public function comment_approve_action_stays_green_semantic_success_unaffected(): void
    {
        // CommentResource approve action — explicitly listed in AI-816's
        // exempt list as "approved" post-submit semantic-success state.
        $source = $this->fileContents('Modules/Comments/Filament/Resources/CommentResource.php');
        $anchor = "Action::make('approve')";
        $pos = strpos($source, $anchor);
        $this->assertNotFalse($pos, 'Comment approve action must exist for the semantic-stays-green regression guard');
        $slice = substr($source, $pos, 800);
        $this->assertStringContainsString(
            "->color('success')",
            $slice,
            'AI-816 must NOT regress the Comment approve action: it is genuinely success-semantic and stays green'
        );
    }

    #[Test]
    public function product_pricing_activate_bulk_stays_green_publish_precedent(): void
    {
        // ProductPricingRuleResource Activate bulk action — Publish-precedent:
        // transitions to active state, semantic-success per AI-816 exempt list.
        $source = $this->fileContents('Modules/Product/Filament/Admin/Resources/ProductPricingRuleResource.php');
        $anchor = "BulkAction::make('activate')";
        $pos = strpos($source, $anchor);
        $this->assertNotFalse($pos, 'Activate bulk action must exist for the semantic-stays-green regression guard');
        $slice = substr($source, $pos, 800);
        $this->assertStringContainsString(
            "->color('success')",
            $slice,
            'AI-816 must NOT regress the Activate bulk action: Publish-precedent semantic-success stays green'
        );
    }
}
