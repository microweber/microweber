<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-admrows — Filament admin table row touch-target floors.
 *
 * DEFECTS (probed at 390x844 on /admin/pages — recurs on every Filament
 * resource list using the content-prefix row layout)
 *
 *   1. Title link `<a class="block truncate">` — 293x20 pre-fix.
 *      Anchor wraps record title; primary navigation target. Width fine,
 *      height fails WCAG 2.5.5 / iOS HIG 44x44.
 *
 *   2. Status badge dropdown trigger inside `.fi-ta-dropdown` — 146x28.
 *      Existing `.fi-ta-content .fi-btn.fi-color-success` and
 *      `.fi-ta-record .fi-btn.fi-size-sm.fi-color-success` rules pin
 *      BOTH min-height AND height to 28px with !important — needs
 *      override of both properties (height wins over min-height when
 *      both are set on the same element).
 *
 *   3. Table checkboxes `.fi-ta-record-checkbox` + `.fi-ta-page-checkbox`
 *      — 18x18 (both dims fail). Filament emits bare <input> with no
 *      `<label>` wrapper, so the AI-517 / AI-518 form-checkbox pattern
 *      (`label:has(.fi-checkbox-input)`) doesn't catch them. Lift the
 *      input itself to 44x44.
 *
 * FIX
 *   `packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css`
 *   carries rules inside the canonical
 *   `@media (max-width: 768px), (pointer: coarse)` block. Each rule
 *   scoped via `body.fi-panel-admin` for specificity bump.
 *
 * TIER-3 POST-FIX (forced-reload probe at 390x844)
 *   - title link: 267x44 (display: flex, min-height: 44px)
 *   - status badge trigger: 146x44 (height: auto override, min-height: 44px)
 *   - row checkbox: 44x44
 *   - header checkbox: 44x44
 */
class AdminAdmrowsTableRowTouchTargetContractTest extends TestCase
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
    public function admrows_block_lives_inside_canonical_mobile_media(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*,\s*\(\s*pointer:\s*coarse\s*\)\s*\{[\s\S]*?body\.fi-panel-admin\s+\.fi-ta-content\s+a\.block\.truncate/s',
            $this->cssStripped,
            'Title link rule must live inside the canonical (max-width: 768px), (pointer: coarse) media block.'
        );
    }

    #[Test]
    public function title_link_pinned_to_44px_flex_for_full_row_width(): void
    {
        // display: flex (NOT inline-flex) so the anchor fills its parent
        // width instead of shrinking to content.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-content\s+a\.block\.truncate\s*\{[^}]*display:\s*flex\s*!important[^}]*min-height:\s*44px\s*!important[^}]*\}/s',
            $this->cssStripped,
            'Title link rule must use display: flex !important + min-height: 44px !important.'
        );
    }

    #[Test]
    public function badge_rule_overrides_both_min_height_and_height(): void
    {
        // The badge fights two existing source rules that pin BOTH
        // min-height: 28px AND height: 28px with !important. Override
        // requires both properties — height wins over min-height when
        // both are set.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-content\s+\.fi-btn\.fi-color-success[\s\S]*?body\.fi-panel-admin\s+\.fi-ta-record\s+\.fi-btn\.fi-size-sm\.fi-color-success[\s\S]*?body\.fi-panel-admin\s+\.fi-ta-dropdown\s+\.fi-dropdown-trigger\s*>\s*button\s*\{[^}]*min-height:\s*44px\s*!important[^}]*height:\s*auto\s*!important[^}]*\}/s',
            $this->cssStripped,
            'Badge dropdown trigger rule must override both min-height (44px) and height (auto) on all three selectors that pin badges at 28px.'
        );
    }

    #[Test]
    public function table_checkboxes_lifted_to_44_by_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-record-checkbox\s*,\s*body\.fi-panel-admin\s+\.fi-ta-page-checkbox\s*\{[^}]*min-width:\s*44px\s*!important[^}]*min-height:\s*44px\s*!important[^}]*width:\s*44px\s*!important[^}]*height:\s*44px\s*!important[^}]*\}/s',
            $this->cssStripped,
            'Table checkboxes (record + page) must be lifted to 44x44 via min-width/min-height/width/height all !important.'
        );
    }

    #[Test]
    public function admrows_block_carries_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-30-admrows', $this->css);
    }

    #[Test]
    public function admrows_rules_are_scoped_to_admin_panel_not_global(): void
    {
        // Defence: rules must NOT escape the body.fi-panel-admin scope.
        // Checkout / profile panels keep their own table styles.
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*\.fi-ta-record-checkbox\s*\{[^}]*min-height:\s*44px/m',
            $this->cssStripped,
            'Table-row touch-target rules must NOT exist at unscoped global level.'
        );
    }
}
