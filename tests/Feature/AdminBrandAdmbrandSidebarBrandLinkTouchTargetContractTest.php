<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-admbrand — Filament admin sidebar brand-link touch target.
 *
 * DEFECT
 *   Stock Filament v5 sidebar header emits the brand-link as
 *   `<a><div class="fi-logo">Brand</div></a>` with no padding on the
 *   anchor. At 390x844 the anchor renders 106x20 — failing WCAG 2.5.5 /
 *   iOS HIG 44x44 touch-target floor.
 *
 *   Tier-3 forced-reload probe at 390x844: pre-fix `getBoundingClientRect`
 *   returned `{ w: 106, h: 20 }` with `min-height: 0px`.
 *
 * FIX
 *   `packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css`
 *   carries a `body.fi-panel-admin .fi-sidebar-header-logo-ctn a` rule
 *   inside the canonical `@media (max-width: 768px), (pointer: coarse)`
 *   block. Three declarations pinned with !important:
 *
 *     display: inline-flex !important;
 *     align-items: center !important;
 *     min-height: 44px !important;
 *
 *   Specificity-bumped via `body.fi-panel-admin` prefix because Filament's
 *   own sidebar header rules ship LATER in the cascade and would
 *   otherwise win source-order at the same specificity.
 *
 *   Tier-3 post-fix probe: `{ w: 106, h: 44 }` with `min-height: 44px;
 *   display: inline-flex`.
 */
class AdminBrandAdmbrandSidebarBrandLinkTouchTargetContractTest extends TestCase
{
    private string $css;
    private string $cssStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css'
        ));

        // Strip CSS block comments so docblock prose mentioning rule
        // selectors does not satisfy negative assertions on its own.
        $this->cssStripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->css);
    }

    #[Test]
    public function brand_link_rule_pinned_44px_inside_mobile_media(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*,\s*\(\s*pointer:\s*coarse\s*\)\s*\{[\s\S]*?body\.fi-panel-admin\s+\.fi-sidebar-header-logo-ctn\s+a\s*\{[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $this->css,
            'Brand link rule must pin min-height:44px !important with body.fi-panel-admin specificity bump inside the mobile media block.'
        );
    }

    #[Test]
    public function brand_link_rule_uses_inline_flex_for_vertical_centering(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-sidebar-header-logo-ctn\s+a\s*\{[^}]*display:\s*inline-flex\s*!important/s',
            $this->css,
            'Brand link rule must use display: inline-flex !important so the brand mark stays vertically centered.'
        );
    }

    #[Test]
    public function brand_link_rule_centers_align_items(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-sidebar-header-logo-ctn\s+a\s*\{[^}]*align-items:\s*center\s*!important/s',
            $this->css,
            'Brand link rule must align-items: center so the brand mark sits visually centered in the expanded 44px hit area.'
        );
    }

    #[Test]
    public function brand_link_rule_carries_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-30-admbrand', $this->css);
    }

    #[Test]
    public function brand_link_rule_is_scoped_to_admin_panel_not_global(): void
    {
        // Defence: the rule must NOT escape the body.fi-panel-admin scope
        // and apply globally — checkout / profile panels keep their own
        // sidebar header geometry.
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.fi-sidebar-header-logo-ctn\s+a\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'Brand link rule must NOT exist at unscoped global level — must be scoped via body.fi-panel-admin.'
        );
    }
}
