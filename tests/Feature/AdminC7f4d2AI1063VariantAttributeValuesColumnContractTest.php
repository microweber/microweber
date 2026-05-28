<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-28-c7f4d2 / AI-1063 — Variant Attributes VALUES column showed
 * count "(1)" instead of actual value names (S, M, L, XL).
 * Jira: https://microweber.atlassian.net/browse/AI-1063
 *
 * Pre-fix: ProductVariantAttributeResource VALUES column used
 * ->counts('values') which emits only the integer row count.
 *
 * Fix: replaced with a virtual TextColumn::make('values_list')
 * using ->getStateUsing() that plucks the 'value' field from the
 * eager-loaded values collection and joins with ', '.
 * getEloquentQuery() eager-loads the 'values' relation to avoid N+1.
 *
 * Source-of-truth:
 *   Modules/Product/Filament/Admin/Resources/ProductVariantAttributeResource.php
 *
 * Selector-self-match guard: setUp() pre-strips PHP block comments
 * before any absence assertion (uniformity-rule from task-7aa48a).
 */
class AdminC7f4d2AI1063VariantAttributeValuesColumnContractTest extends TestCase
{
    private const SOURCE = 'Modules/Product/Filament/Admin/Resources/ProductVariantAttributeResource.php';

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
    // Group A — VALUES column uses getStateUsing (not counts)
    // -----------------------------------------------------------------------

    #[Test]
    public function values_column_uses_get_state_using(): void
    {
        $this->assertMatchesRegularExpression(
            '/TextColumn::make\(\'values_list\'\)[\s\S]{0,300}?->getStateUsing\(/',
            $this->source,
            'AI-1063: VALUES column MUST use ->getStateUsing() to render comma-separated value names.'
        );
    }

    #[Test]
    public function values_column_plucks_value_field(): void
    {
        $this->assertMatchesRegularExpression(
            '/getStateUsing[\s\S]{0,300}?->pluck\(\'value\'\)/',
            $this->source,
            "AI-1063: ->getStateUsing() MUST pluck the 'value' field from the values relation."
        );
    }

    #[Test]
    public function values_column_implodes_with_comma(): void
    {
        $this->assertMatchesRegularExpression(
            '/getStateUsing[\s\S]{0,300}?->implode\(\s*[\'"],\s*[\'"]/',
            $this->source,
            "AI-1063: ->getStateUsing() MUST implode the plucked values with ', ' separator."
        );
    }

    #[Test]
    public function values_column_has_dash_fallback(): void
    {
        $this->assertMatchesRegularExpression(
            '/getStateUsing[\s\S]{0,300}?[?:]\s*[\'"]—[\'"]/',
            $this->source,
            "AI-1063: ->getStateUsing() MUST fall back to '—' when no values exist."
        );
    }

    // -----------------------------------------------------------------------
    // Group B — Eager loading present for N+1 prevention
    // -----------------------------------------------------------------------

    #[Test]
    public function eager_loading_values_relation_present(): void
    {
        $this->assertMatchesRegularExpression(
            "/getEloquentQuery\(\)[\s\S]{0,200}?->with\(\[[\s\S]{0,50}?'values'[\s\S]{0,50}?\]\)/",
            $this->source,
            "AI-1063: getEloquentQuery() MUST eager-load the 'values' relation to avoid N+1 queries."
        );
    }

    // -----------------------------------------------------------------------
    // Group C — Legacy ->counts() absent (comment-stripped negative)
    // -----------------------------------------------------------------------

    #[Test]
    public function legacy_counts_values_absent(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/->counts\(\s*\'values\'\s*\)/',
            $this->sourceStripped,
            "AI-1063: legacy ->counts('values') MUST be replaced by ->getStateUsing() which renders actual value names."
        );
    }

    // -----------------------------------------------------------------------
    // Group D — Task-id markers present
    // -----------------------------------------------------------------------

    #[Test]
    public function task_id_marker_present_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-c7f4d2',
            $this->source,
            'AI-1063: source MUST carry the AI-1063 task-id marker for cross-surface audit grep.'
        );
        $this->assertStringContainsString(
            'AI-1063',
            $this->source,
            'AI-1063: source MUST cite the AI-1063 ticket ID.'
        );
    }
}
