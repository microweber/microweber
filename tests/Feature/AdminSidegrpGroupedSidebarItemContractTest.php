<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-sidegrp — Filament v5 grouped icon-less .fi-sidebar-item-btn
 * touch-target floor on mobile.
 *
 * DEFECT (probed at 390x844 on /admin/newsletter sidebar opened via the
 * .fi-topbar-open-sidebar-btn hamburger)
 *
 *   .fi-sidebar-item-btn inside a sidebar nav group (carries the
 *   .fi-sidebar-item-grouped-border wrapper) ships at 151x40 — 4px under
 *   the 44px floor. Top-level icon-bearing items render at 151x44 because
 *   their .fi-icon.fi-size-lg icon-box exceeds line-height; grouped
 *   indented sub-items (padding-inline-start: 52px, no icon) settle at
 *   text line-height 20px + padding-block 10px + 10px = 40px. Affects any
 *   Filament v5 admin sidebar group with icon-less sub-items.
 *
 * SCOPE NOTE
 *   Rule carries body[class*="fi-panel-admin"] attribute-substring scope
 *   (mirrors sibling task-2026-05-30-fitasort + mediatap + fipprows
 *   blocks) so the fix reaches the core admin panel AND any sibling
 *   admin sub-panel (admin-newsletter etc.) without leaking to checkout
 *   or profile panels.
 *
 * FIX
 *   packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css
 *   carries the rule inside the canonical @media (max-width: 768px),
 *   (pointer: coarse) block.
 */
class AdminSidegrpGroupedSidebarItemContractTest extends TestCase
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
    public function sidegrp_block_lives_inside_canonical_mobile_media(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*,\s*\(\s*pointer:\s*coarse\s*\)\s*\{[\s\S]*?body\[class\*="fi-panel-admin"\]\s+\.fi-sidebar-item-btn/s',
            $this->cssStripped,
            'fi-sidebar-item-btn rule must live inside the canonical (max-width: 768px), (pointer: coarse) media block.'
        );
    }

    #[Test]
    public function sidebar_item_btn_lifted_to_44px_min_height(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.fi-sidebar-item-btn\s*\{[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $this->cssStripped,
            '.fi-sidebar-item-btn rule must lift min-height to 44px !important.'
        );
    }

    #[Test]
    public function sidegrp_block_uses_attribute_substring_scope_not_class_selector(): void
    {
        // Defence: ensure the attribute-substring selector form is used so
        // sibling admin panels (admin-newsletter, admin-shop, ...) inherit
        // the fix. A plain class selector body.fi-panel-admin would NOT
        // match body.fi-panel-admin-newsletter.
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+\.fi-sidebar-item-btn/s',
            $this->cssStripped,
            'sidegrp rule must use the body[class*="fi-panel-admin"] attribute-substring scope.'
        );
    }

    #[Test]
    public function sidegrp_block_carries_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-30-sidegrp', $this->css);
    }

    #[Test]
    public function sidegrp_rules_are_scoped_to_admin_panels_not_global(): void
    {
        // Defence: rule must NOT escape the panel scope. Checkout / profile
        // panels keep their own sidebar styles.
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.fi-sidebar-item-btn\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'fi-sidebar-item-btn touch-target rule must NOT exist at unscoped global level.'
        );
    }
}
