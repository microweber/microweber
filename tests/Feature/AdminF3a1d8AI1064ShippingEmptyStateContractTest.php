<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-28-f3a1d8 / AI-1064 — Shipping Providers list empty state
 * showed retail-shopper imagery from the content module.
 * Jira: https://microweber.atlassian.net/browse/AI-1064
 *
 * Pre-fix: ShippingProviderResource::table() used ->emptyState() closure
 * returning view('modules.content::filament.admin.empty-state') — a
 * content-module view that renders retail shopper imagery with no relation
 * to shipping configuration context.
 *
 * Fix: replaced with Filament built-in empty state methods:
 *   ->emptyStateIcon('heroicon-o-truck')
 *   ->emptyStateHeading('No shipping providers configured')
 *   ->emptyStateDescription('...')
 *   ->emptyStateActions([CreateAction::make()])
 *
 * Source-of-truth:
 *   Modules/Shipping/Filament/Admin/Resources/ShippingProviderResource.php
 *
 * Selector-self-match guard: setUp() pre-strips PHP block comments
 * before any absence assertion (uniformity-rule from task-7aa48a).
 */
class AdminF3a1d8AI1064ShippingEmptyStateContractTest extends TestCase
{
    private const SOURCE = 'Modules/Shipping/Filament/Admin/Resources/ShippingProviderResource.php';

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
    // Group A — Built-in empty-state methods present
    // -----------------------------------------------------------------------

    #[Test]
    public function empty_state_icon_uses_truck(): void
    {
        $this->assertStringContainsString(
            "->emptyStateIcon('heroicon-o-truck')",
            $this->source,
            "AI-1064: ->emptyStateIcon() MUST use 'heroicon-o-truck' for shipping context."
        );
    }

    #[Test]
    public function empty_state_heading_present(): void
    {
        $this->assertStringContainsString(
            '->emptyStateHeading(',
            $this->source,
            'AI-1064: ->emptyStateHeading() MUST be present to give the empty state a shipping-relevant title.'
        );
    }

    #[Test]
    public function empty_state_description_present(): void
    {
        $this->assertStringContainsString(
            '->emptyStateDescription(',
            $this->source,
            'AI-1064: ->emptyStateDescription() MUST be present to explain what the user should do.'
        );
    }

    #[Test]
    public function empty_state_actions_present(): void
    {
        $this->assertStringContainsString(
            '->emptyStateActions(',
            $this->source,
            'AI-1064: ->emptyStateActions() MUST be present to provide a call-to-action on the empty state.'
        );
    }

    // -----------------------------------------------------------------------
    // Group B — Legacy content-module view absent (comment-stripped negative)
    // -----------------------------------------------------------------------

    #[Test]
    public function legacy_content_module_empty_state_view_absent(): void
    {
        $this->assertStringNotContainsString(
            "modules.content::filament.admin.empty-state",
            $this->sourceStripped,
            "AI-1064: legacy content-module empty-state view MUST be replaced — it rendered retail shopper imagery unrelated to shipping configuration."
        );
    }

    #[Test]
    public function legacy_empty_state_closure_absent(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/->emptyState\s*\(\s*function\s*\(/',
            $this->sourceStripped,
            "AI-1064: legacy ->emptyState(function(...) { return view(...); }) closure MUST be replaced by Filament built-in empty state methods."
        );
    }

    // -----------------------------------------------------------------------
    // Group C — Task-id markers present
    // -----------------------------------------------------------------------

    #[Test]
    public function task_id_marker_present_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-f3a1d8',
            $this->source,
            'AI-1064: source MUST carry the AI-1064 task-id marker for cross-surface audit grep.'
        );
        $this->assertStringContainsString(
            'AI-1064',
            $this->source,
            'AI-1064: source MUST cite the AI-1064 ticket ID.'
        );
    }
}
