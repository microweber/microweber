<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-fitabs — Filament v5 .fi-tabs-item short-label width
 * touch-target floor on mobile.
 *
 * DEFECT (probed at 390x844 on /admin/orders status-filter tabs)
 *
 *   .fi-tabs-item renders at text-line-width + horizontal padding. Short
 *   labels collapse to 27x44 ("All") / 36x44 ("New") — height passes the
 *   44px floor but inline-axis width fails by 17/8px. Long labels
 *   ("Cancelled", "Refunded") already exceed 44px naturally. Affects any
 *   Filament v5 resource list page using status filter tabs with short
 *   labels.
 *
 * SCOPE NOTE
 *   Rule carries body[class*="fi-panel-admin"] attribute-substring scope
 *   (mirrors sibling task-2026-05-30-fitasort + mediatap + fipprows +
 *   sidegrp blocks) so the fix reaches the core admin panel AND any
 *   sibling admin sub-panel (admin-newsletter etc.) without leaking to
 *   checkout or profile panels.
 *
 * FIX
 *   packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css
 *   carries the rule inside the canonical @media (max-width: 768px),
 *   (pointer: coarse) block.
 */
class AdminFitabsShortLabelWidthContractTest extends TestCase
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
        // selectors does not satisfy positive or negative assertions on its
        // own (selector-self-match guard).
        $this->cssStripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->css);
    }

    #[Test]
    public function fitabs_block_lives_inside_canonical_mobile_media(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*,\s*\(\s*pointer:\s*coarse\s*\)\s*\{[\s\S]*?body\[class\*="fi-panel-admin"\]\s+\.fi-tabs-item/s',
            $this->cssStripped,
            'fi-tabs-item rule must live inside the canonical (max-width: 768px), (pointer: coarse) media block.'
        );
    }

    #[Test]
    public function tabs_item_lifted_to_44px_inline_flex(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.fi-tabs-item\s*\{[^}]*min-width:\s*44px\s*!important[^}]*min-height:\s*44px\s*!important[^}]*display:\s*inline-flex\s*!important[^}]*align-items:\s*center\s*!important[^}]*justify-content:\s*center\s*!important[^}]*\}/s',
            $this->cssStripped,
            '.fi-tabs-item rule must lift min-width + min-height to 44px via inline-flex + center alignment all !important.'
        );
    }

    #[Test]
    public function fitabs_block_uses_attribute_substring_scope_not_class_selector(): void
    {
        // Defence: ensure the attribute-substring selector form is used so
        // sibling admin panels (admin-newsletter, admin-shop, ...) inherit
        // the fix. A plain class selector body.fi-panel-admin would NOT
        // match body.fi-panel-admin-newsletter.
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.fi-tabs-item/s',
            $this->cssStripped,
            'fitabs rule must use the body[class*="fi-panel-admin"] attribute-substring scope.'
        );
    }

    #[Test]
    public function fitabs_block_carries_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-30-fitabs', $this->css);
    }

    #[Test]
    public function fitabs_rules_are_scoped_to_admin_panels_not_global(): void
    {
        // Defence: rule must NOT escape the panel scope. Checkout / profile
        // panels keep their own tabs styles.
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.fi-tabs-item\s*\{[^}]*min-width:\s*44px/m',
            $this->cssStripped,
            'fi-tabs-item touch-target rule must NOT exist at unscoped global level.'
        );
    }
}
