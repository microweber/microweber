<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-fipprows — Filament v5 pagination "records per page"
 * select touch-target floor on mobile.
 *
 * DEFECT (probed at 390x844 on /admin/users + /admin/posts)
 *
 *   The .fi-pagination-records-per-page-select wrapper that hosts the
 *   rows-per-page native <select> ships at 62x34 (outer label) / 60x32
 *   (inner select) — height fails the 44px floor on the actual tap target.
 *   Sibling .fi-pagination-next-btn / .fi-pagination-previous-btn already
 *   pass at 81x44 via Filament's default fi-size-md geometry, and
 *   .fi-pagination-item-btn renders 0x0 on mobile by design.
 *
 * SCOPE NOTE
 *   Rule carries body[class*="fi-panel-admin"] attribute-substring scope
 *   (mirrors the sibling task-2026-05-30-fitasort + task-2026-05-30-mediatap
 *   blocks) so the fix reaches the core admin panel AND any sibling admin
 *   sub-panel (admin-newsletter etc.) without leaking to checkout / profile
 *   panels which carry their own pagination chrome.
 *
 * FIX
 *   packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css
 *   carries the rule inside the canonical @media (max-width: 768px),
 *   (pointer: coarse) block.
 */
class AdminFipprowsPaginationPerPageSelectContractTest extends TestCase
{
    private string $css;
    private string $cssStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css'
        ));

        // Pre-strip CSS block comments so docblock prose mentioning rule
        // selectors does not satisfy negative or positive assertions on
        // its own (selector-self-match guard).
        $this->cssStripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->css);
    }

    #[Test]
    public function fipprows_block_lives_inside_canonical_mobile_media(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*,\s*\(\s*pointer:\s*coarse\s*\)\s*\{[\s\S]*?body\[class\*="fi-panel-admin"\]\s+label\.fi-pagination-records-per-page-select/s',
            $this->cssStripped,
            'fi-pagination-records-per-page-select rule must live inside the canonical (max-width: 768px), (pointer: coarse) media block.'
        );
    }

    #[Test]
    public function per_page_select_lifted_to_44px_height_at_three_levels(): void
    {
        // Three selectors grouped in a single multi-selector rule:
        // outer label, inner .fi-input-wrp, native select.fi-select-input.
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+label\.fi-pagination-records-per-page-select\s*,\s*body\[class\*="fi-panel-admin"\]\s+label\.fi-pagination-records-per-page-select\s+\.fi-input-wrp\s*,\s*body\[class\*="fi-panel-admin"\]\s+label\.fi-pagination-records-per-page-select\s+select\.fi-select-input\s*\{[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $this->cssStripped,
            'per-page select must lift outer label + inner .fi-input-wrp + native select all to 44px min-height !important in a single multi-selector rule.'
        );
    }

    #[Test]
    public function per_page_label_uses_inline_flex_with_center_alignment(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+label\.fi-pagination-records-per-page-select\s*\{[^}]*display:\s*inline-flex\s*!important[^}]*align-items:\s*center\s*!important[^}]*\}/s',
            $this->cssStripped,
            'outer label.fi-pagination-records-per-page-select must use inline-flex + align-items: center both !important so the lifted height centres the contained select visually.'
        );
    }

    #[Test]
    public function fipprows_block_uses_attribute_substring_scope_not_class_selector(): void
    {
        // Defence: ensure the attribute-substring selector form is used so
        // sibling admin panels (admin-newsletter, admin-shop, ...) inherit
        // the fix. A plain class selector body.fi-panel-admin would NOT
        // match body.fi-panel-admin-newsletter.
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+label\.fi-pagination-records-per-page-select/s',
            $this->cssStripped,
            'fipprows rule must use the body[class*="fi-panel-admin"] attribute-substring scope.'
        );
    }

    #[Test]
    public function fipprows_block_carries_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-30-fipprows', $this->css);
    }

    #[Test]
    public function fipprows_rules_are_scoped_to_admin_panels_not_global(): void
    {
        // Defence: rules must NOT escape the panel scope. Checkout / profile
        // panels keep their own pagination styles.
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*label\.fi-pagination-records-per-page-select\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'fi-pagination-records-per-page-select touch-target rule must NOT exist at unscoped global level.'
        );
    }
}
