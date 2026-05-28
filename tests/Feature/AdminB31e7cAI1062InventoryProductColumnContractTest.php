<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-28-b31e7c / AI-1062 — Inventory list blank PRODUCT column.
 * Jira: https://microweber.atlassian.net/browse/AI-1062
 *
 * Pre-fix: ProductInventoryResource PRODUCT column used ->default() as the
 * null-only fallback. Content titles that are empty strings (not null)
 * passed through unhandled and rendered blank cells.
 *
 * AI-1102 (task-2026-05-26) established the eager load ->with(['product'])
 * and added ->default() for null product_id rows. AI-1062 upgrades the
 * column renderer to ->formatStateUsing() so both null AND empty-string
 * states fall back to a readable "Product #ID" label.
 *
 * The product relationship: ProductInventoryMovement->product() is
 * BelongsTo(Content::class, 'product_id') — products ARE content items
 * in Microweber (content_type='product').
 *
 * Source-of-truth: Modules/Product/Filament/Admin/Resources/ProductInventoryResource.php
 *
 * Selector-self-match guard: setUp() pre-strips PHP block comments
 * before any absence assertion (uniformity-rule from task-7aa48a).
 */
class AdminB31e7cAI1062InventoryProductColumnContractTest extends TestCase
{
    private const SOURCE = 'Modules/Product/Filament/Admin/Resources/ProductInventoryResource.php';

    private string $source;

    private string $sourceStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->source = (string) file_get_contents(base_path(self::SOURCE));
        $this->sourceStripped = (string) preg_replace('~/\*[\s\S]*?\*/~', '', $this->source);
        $this->sourceStripped = (string) preg_replace('~//[^\n]*~', '', $this->sourceStripped);
    }

    // -----------------------------------------------------------------------
    // Group A — Product column uses formatStateUsing (not default-only)
    // -----------------------------------------------------------------------

    #[Test]
    public function product_column_uses_format_state_using(): void
    {
        $this->assertMatchesRegularExpression(
            '/TextColumn::make\(\'product\.title\'\)[\s\S]{0,400}?->formatStateUsing\(/',
            $this->source,
            'AI-1062: PRODUCT column MUST use ->formatStateUsing() to handle both null and empty-string product titles.'
        );
    }

    #[Test]
    public function format_state_handles_empty_string_case(): void
    {
        $this->assertStringContainsString(
            "\$state !== ''",
            $this->source,
            "AI-1062: formatStateUsing MUST explicitly check for empty-string state (\$state !== '') so empty-string product titles fall through to the fallback."
        );
    }

    #[Test]
    public function format_state_provides_product_id_fallback(): void
    {
        $this->assertMatchesRegularExpression(
            '/formatStateUsing[\s\S]{0,400}?product_id/',
            $this->source,
            "AI-1062: formatStateUsing fallback MUST reference product_id to produce a 'Product #ID' label when title is blank."
        );
    }

    // -----------------------------------------------------------------------
    // Group B — Eager loading still present for the relationship
    // -----------------------------------------------------------------------

    #[Test]
    public function eager_loading_product_relation_present(): void
    {
        $this->assertMatchesRegularExpression(
            "/getEloquentQuery\(\)[\s\S]{0,200}?->with\(\[[\s\S]{0,50}?'product'[\s\S]{0,50}?\]\)/",
            $this->source,
            "AI-1062: getEloquentQuery() MUST eager-load the 'product' relation so the column renderer can access product->title without N+1 queries."
        );
    }

    // -----------------------------------------------------------------------
    // Group C — Legacy ->default() lambda absent (comment-stripped negative)
    // -----------------------------------------------------------------------

    #[Test]
    public function legacy_default_lambda_absent(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/->default\(fn\s*\(\$record\)\s*=>/',
            $this->sourceStripped,
            'AI-1062: legacy ->default(fn ($record) => ...) fallback MUST be replaced by ->formatStateUsing() which handles both null and empty-string states.'
        );
    }

    // -----------------------------------------------------------------------
    // Group D — Task-id markers present
    // -----------------------------------------------------------------------

    #[Test]
    public function task_id_marker_present_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-b31e7c',
            $this->source,
            'AI-1062: source MUST carry the AI-1062 task-id marker for cross-surface audit grep.'
        );
        $this->assertStringContainsString(
            'AI-1062',
            $this->source,
            'AI-1062: source MUST cite the AI-1062 ticket ID.'
        );
    }
}
