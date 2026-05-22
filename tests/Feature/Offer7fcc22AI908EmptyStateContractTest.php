<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-7fcc22 / AI-908 — Offers admin list empty state.
 *
 * Problem: /admin/offers rendered a blank table body when no records exist.
 * The existing ->emptyState(function...) used the Content module's generic
 * empty-state view which had no branch for Modules\Offer\Models\Offer,
 * so it rendered an empty <div> with no heading, no description, no CTA.
 *
 * Fix: replaced the custom view callback with Filament-native methods:
 *   ->emptyStateHeading('No offers yet')
 *   ->emptyStateDescription('Create your first offer to discount specific products.')
 *   ->emptyStateActions([Tables\Actions\CreateAction::make()->label('+ New Offer')])
 *
 * Style: file-system reads only — no DB / Filament boot.
 */
class Offer7fcc22AI908EmptyStateContractTest extends TestCase
{
    private const OFFER_RESOURCE = 'Modules/Offer/Filament/Admin/Resources/OfferResource.php';

    private string $src;
    private string $srcStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->src = (string) file_get_contents(base_path(self::OFFER_RESOURCE));

        $s = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->src) ?? $this->src;
        $this->srcStripped = preg_replace('~//[^\n]*~', '', $s) ?? $s;
    }

    #[Test]
    public function empty_state_heading_is_set(): void
    {
        $this->assertStringContainsString(
            "emptyStateHeading('No offers yet')",
            $this->srcStripped,
            'OfferResource must declare ->emptyStateHeading(\'No offers yet\').'
        );
    }

    #[Test]
    public function empty_state_description_is_set(): void
    {
        $this->assertStringContainsString(
            'emptyStateDescription(',
            $this->srcStripped,
            'OfferResource must declare ->emptyStateDescription(...).'
        );
        $this->assertStringContainsString(
            'first offer',
            $this->srcStripped,
            'emptyStateDescription must mention creating a first offer.'
        );
    }

    #[Test]
    public function empty_state_actions_include_create_action(): void
    {
        $this->assertStringContainsString(
            'emptyStateActions(',
            $this->srcStripped,
            'OfferResource must declare ->emptyStateActions([...]).'
        );
        $this->assertStringContainsString(
            'CreateAction::make()',
            $this->srcStripped,
            'emptyStateActions must include Tables\\Actions\\CreateAction::make().'
        );
    }

    #[Test]
    public function old_generic_content_empty_state_view_removed(): void
    {
        // The Content module view had no Offers branch — it silently rendered
        // an empty <div>. The new native methods replace it.
        $this->assertDoesNotMatchRegularExpression(
            "~->emptyState\s*\(.*?modules\.content::filament\.admin\.empty-state~s",
            $this->srcStripped,
            'The generic Content empty-state view callback must be replaced by native methods.'
        );
    }

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-7fcc22',
            $this->src,
            'OfferResource must carry the AI-908 task marker.'
        );
    }
}
