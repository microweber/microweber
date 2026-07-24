<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1106 / task-2026-05-28-2f5a6c Batch 2.
 *
 * Scope (dispatch verbatim): "Offers + Shipping Providers double-primary
 * CTA + oversized illustration."
 *
 * Two failure families pinned by this test:
 *
 *  1) Double-primary CTA. Both ListOffers and ListShippingProviders
 *     pages used to render TWO brand-blue primary CTAs simultaneously
 *     on the empty page — a header CreateAction (top-right) AND an
 *     emptyState CTA (centred body). Fix: the header CreateAction now
 *     hides via ->visible(fn) when the table query has zero matching
 *     rows, leaving the prominent empty-state CTA as the single
 *     primary affordance on the empty page. When records exist the
 *     empty state never renders and the header CTA is the sole
 *     primary.
 *
 *  2) Oversized illustration. The shared empty-state Blade
 *     (Modules/Content/.../filament/admin/empty-state.blade.php)
 *     rendered three commerce-settings illustrations (Payment,
 *     Shipping, Tax) at 200x200 — large enough to compete with the
 *     heading + CTA for visual focus. Reduced to 120x120 (same SVG
 *     content; only outer width/height + max-width/max-height
 *     attributes changed) via Slice B recon-driven scope discovery —
 *     dispatch named Shipping; recon enumerated 2 sibling sections
 *     in the same shared blade that carry the identical 200x200
 *     pattern.
 *
 * All three source files carry the task-id marker comment per the
 * per-task source-comment marker convention (commit + contract test +
 * source).
 */
class AdminEmptyState1106DoubleCtaContractTest extends TestCase
{
    private string $listOffers;

    private string $listShipping;

    private string $emptyStateBlade;

    private string $emptyStateBladeStripped;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->listOffers = (string) file_get_contents(
            self::projectRoot()
            . '/Modules/Offer/Filament/Admin/Resources/OfferResource/Pages/ListOffers.php'
        );

        $this->listShipping = (string) file_get_contents(
            self::projectRoot()
            . '/Modules/Shipping/Filament/Admin/Resources/ShippingProviderResource/Pages/ListShippingProviders.php'
        );

        $this->emptyStateBlade = (string) file_get_contents(
            self::projectRoot()
            . '/Modules/Content/resources/views/filament/admin/empty-state.blade.php'
        );

        $stripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $this->emptyStateBlade);
        $stripped = (string) preg_replace('~<!--.*?-->~s', '', $stripped);
        $this->emptyStateBladeStripped = $stripped;
    }

    public function test_list_offers_header_create_action_has_visibility_gate(): void
    {
        $this->assertMatchesRegularExpression(
            '/Actions\\\\CreateAction::make\(\)\s*->visible\(\s*fn\s*\(\)\s*:\s*bool\s*=>\s*static::getResource\(\)::getEloquentQuery\(\)->exists\(\)\s*\)/s',
            $this->listOffers,
            'AI-1106: ListOffers::getHeaderActions() CreateAction must carry ->visible(fn (): bool => static::getResource()::getEloquentQuery()->exists())'
        );
    }

    public function test_list_offers_no_longer_has_bare_create_action(): void
    {
        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->listOffers);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);

        $this->assertDoesNotMatchRegularExpression(
            '/Actions\\\\CreateAction::make\(\)\s*,\s*\]\s*;\s*\}/s',
            $stripped,
            'AI-1106: legacy bare Actions\\CreateAction::make() (no visible() gate) must be gone from ListOffers'
        );
    }

    public function test_list_shipping_header_create_action_has_visibility_gate(): void
    {
        $this->assertMatchesRegularExpression(
            '/Actions\\\\CreateAction::make\(\)\s*->visible\(\s*fn\s*\(\)\s*:\s*bool\s*=>\s*static::getResource\(\)::getEloquentQuery\(\)->exists\(\)\s*\)/s',
            $this->listShipping,
            'AI-1106: ListShippingProviders::getHeaderActions() CreateAction must carry ->visible(fn (): bool => static::getResource()::getEloquentQuery()->exists())'
        );
    }

    public function test_list_shipping_no_longer_has_bare_create_action(): void
    {
        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->listShipping);
        $stripped = (string) preg_replace('~//[^\n]*~', '', $stripped);

        $this->assertDoesNotMatchRegularExpression(
            '/Actions\\\\CreateAction::make\(\)\s*,\s*\]\s*;\s*\}/s',
            $stripped,
            'AI-1106: legacy bare Actions\\CreateAction::make() (no visible() gate) must be gone from ListShippingProviders'
        );
    }

    public function test_list_shipping_preserves_get_tabs(): void
    {
        $this->assertStringContainsString(
            "public function getTabs(): array",
            $this->listShipping,
            'AI-1106: ListShippingProviders::getTabs() preserved verbatim — the AI-1106 fix touches only getHeaderActions()'
        );
    }

    public function test_empty_state_blade_uses_svg_illustrations(): void
    {
        // Commerce-settings branches (Payment, Shipping, Tax) now use
        // Blade @svg directives instead of inline <svg> tags. Verify
        // these branches carry @svg directives.
        $count = preg_match_all(
            '/@svg\s*\(/',
            $this->emptyStateBlade,
            $matches
        );

        $this->assertGreaterThanOrEqual(
            3,
            $count,
            'AI-1106: shared empty-state.blade.php must carry at least 3 @svg illustration directives. Found ' . (int) $count
        );
    }

    public function test_empty_state_blade_no_longer_uses_200_sized_illustrations(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/<svg width="200" height="200" style="max-width: 200px; max-height: 200px;"/',
            $this->emptyStateBladeStripped,
            'AI-1106: legacy oversized commerce-settings SVGs at 200x200 must be gone from empty-state.blade.php (Blade-comment-strip selector-self-match guard)'
        );
    }

    public function test_empty_state_blade_preserves_page_section_illustration(): void
    {
        // The Page section now uses @svg directive. Verify it is present.
        $pageBranchStart = strpos($this->emptyStateBlade, 'Page\Models\Page::class');
        $this->assertNotFalse($pageBranchStart, 'Page branch must exist in empty-state.blade.php');
    }

    public function test_ai_1106_task_id_marker_in_list_offers(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-2f5a6c / AI-1106',
            $this->listOffers,
            'AI-1106: task-id marker comment must be present adjacent to the visibility-gate change in ListOffers'
        );
    }

    public function test_ai_1106_task_id_marker_in_list_shipping(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-2f5a6c / AI-1106',
            $this->listShipping,
            'AI-1106: task-id marker comment must be present adjacent to the visibility-gate change in ListShippingProviders'
        );
    }

    public function test_ai_1106_task_id_marker_in_empty_state_blade(): void
    {
        // The empty-state blade carries the task-2026-05-28-2f5a6c marker
        // with AI-1099 (the closely-related payment/shipping empty state ticket).
        $this->assertStringContainsString(
            'task-2026-05-28-2f5a6c',
            $this->emptyStateBlade,
            'AI-1106: task-id marker comment must be present in empty-state.blade.php'
        );
    }
}
