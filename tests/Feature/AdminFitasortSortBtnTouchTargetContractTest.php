<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-fitasort — Filament table column-sort button + numeric
 * body-cell button touch-target floors on mobile.
 *
 * DEFECTS (probed at 390x844 across Newsletter resource list pages, then
 * scope-confirmed against /admin/users + /admin/orders to bound the family
 * to tables whose columns opt-in via Column::sortable())
 *
 *   1. `.fi-ta-header-cell-sort-btn` ships at 20px height — the clickable
 *      text/icon control inside each sortable `<th>`.
 *
 *   2. `button.fi-ta-col` — body-row cells rendered as buttons (e.g.
 *      numeric subscriber-count column on /admin/newsletter/lists) ship
 *      at 32x56 — narrower than the 44px floor on the inline-axis.
 *
 * SCOPE NOTE
 *   Newsletter ships a sibling Filament panel with id `admin-newsletter`,
 *   so its body class is `fi-panel-admin-newsletter` — the class selector
 *   body.fi-panel-admin does NOT match it (CSS class selectors do not
 *   substring-match). The attribute-substring scope
 *   body[class*="fi-panel-admin"] captures both the core admin panel AND
 *   any sibling admin sub-panel (current or future) without leaking to
 *   fi-panel-checkout / fi-panel-profile.
 *
 * FIX
 *   `packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css`
 *   carries rules inside the canonical
 *   `@media (max-width: 768px), (pointer: coarse)` block. Each rule
 *   scoped via the attribute-substring panel-scope.
 */
class AdminFitasortSortBtnTouchTargetContractTest extends TestCase
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
    public function fitasort_block_lives_inside_canonical_mobile_media(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*,\s*\(\s*pointer:\s*coarse\s*\)\s*\{[\s\S]*?body\[class\*="fi-panel-admin"\]\s+\.fi-ta-header-cell-sort-btn/s',
            $this->cssStripped,
            'fi-ta-header-cell-sort-btn rule must live inside the canonical (max-width: 768px), (pointer: coarse) media block.'
        );
    }

    #[Test]
    public function sort_btn_lifted_to_44px_inline_flex(): void
    {
        // Sort buttons ship at 20px height — lift to 44px via inline-flex
        // + align-items: center + min-height all !important.
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.fi-ta-header-cell-sort-btn\s*\{[^}]*min-height:\s*44px\s*!important[^}]*display:\s*inline-flex\s*!important[^}]*align-items:\s*center\s*!important[^}]*\}/s',
            $this->cssStripped,
            '.fi-ta-header-cell-sort-btn rule must use inline-flex + align-items: center + min-height: 44px all !important.'
        );
    }

    #[Test]
    public function col_btn_lifted_to_44px_inline_axis_and_height(): void
    {
        // Body-cell button (numeric count column) ships at 32x56 — lift
        // inline-axis to 44px min-width and pin min-height.
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+button\.fi-ta-col\s*\{[^}]*min-width:\s*44px\s*!important[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $this->cssStripped,
            'button.fi-ta-col rule must lift min-width to 44px and min-height to 44px both !important.'
        );
    }

    #[Test]
    public function fitasort_block_uses_attribute_substring_scope_not_class_selector(): void
    {
        // Defence: ensure the attribute-substring selector form is used so
        // sibling admin panels (admin-newsletter, admin-shop, …) inherit
        // the fix. A plain class selector body.fi-panel-admin would NOT
        // match body.fi-panel-admin-newsletter.
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.fi-ta-header-cell-sort-btn/s',
            $this->cssStripped,
            'fitasort rules must use the body[class*="fi-panel-admin"] attribute-substring scope.'
        );
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+button\.fi-ta-col/s',
            $this->cssStripped,
            'fitasort rules must use the body[class*="fi-panel-admin"] attribute-substring scope.'
        );
    }

    #[Test]
    public function fitasort_block_carries_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-30-fitasort', $this->css);
    }

    #[Test]
    public function fitasort_rules_are_scoped_to_admin_panels_not_global(): void
    {
        // Defence: rules must NOT escape the panel scope.
        // Checkout / profile panels keep their own table styles.
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.fi-ta-header-cell-sort-btn\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'fi-ta-header-cell-sort-btn touch-target rule must NOT exist at unscoped global level.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*button\.fi-ta-col\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'button.fi-ta-col touch-target rule must NOT exist at unscoped global level.'
        );
    }
}
