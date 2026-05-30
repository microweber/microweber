<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-mediacb — Media grid checkbox 44x44 hit-area on mobile.
 *
 * DEFECT (probed at 390x844 on /admin/media)
 *
 *   label.mw-media-grid-checkbox ships at 16x20 with a native
 *   <input type="checkbox"> 16x16 inside. The label is position:absolute
 *   overlaid on each media tile (bulk-select affordance via
 *   wire:click.stop="toggleBulkSelect(<id>)"). 28px short on each axis
 *   against the 44x44 WCAG 2.5.5 floor.
 *
 * SCOPE NOTE
 *   Rule carries body[class*="fi-panel-admin"] attribute-substring scope
 *   (mirrors sibling task-2026-05-30-mediatap + mediafil blocks). Project-
 *   bespoke .mw-media-* selector cannot leak to Filament .fi-checkbox
 *   chrome.
 *
 * FIX
 *   packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css
 *   carries the rule inside the canonical @media (max-width: 768px),
 *   (pointer: coarse) block. Label expanded to 44x44 hit area via
 *   min-width/min-height + inline-flex centering; the native input stays
 *   16x16 visually but the click target is the whole 44x44 label.
 */
class AdminMediacbGridCheckboxHitAreaContractTest extends TestCase
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
    public function mediacb_block_lives_inside_canonical_mobile_media(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*,\s*\(\s*pointer:\s*coarse\s*\)\s*\{[\s\S]*?body\[class\*="fi-panel-admin"\]\s+label\.mw-media-grid-checkbox/s',
            $this->cssStripped,
            'mw-media-grid-checkbox rule must live inside the canonical (max-width: 768px), (pointer: coarse) media block.'
        );
    }

    #[Test]
    public function grid_checkbox_label_lifted_to_44x44_inline_flex(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+label\.mw-media-grid-checkbox\s*\{[^}]*min-width:\s*44px\s*!important[^}]*min-height:\s*44px\s*!important[^}]*display:\s*inline-flex\s*!important[^}]*align-items:\s*center\s*!important[^}]*justify-content:\s*center\s*!important[^}]*\}/s',
            $this->cssStripped,
            'label.mw-media-grid-checkbox must lift to 44x44 via inline-flex + center alignment all !important.'
        );
    }

    #[Test]
    public function mediacb_block_uses_attribute_substring_scope_not_class_selector(): void
    {
        // Defence: ensure the attribute-substring selector form is used so
        // sibling admin panels (admin-newsletter, admin-shop, ...) inherit
        // the fix.
        $this->assertMatchesRegularExpression(
            '/body\[class\*="fi-panel-admin"\]\s+label\.mw-media-grid-checkbox/s',
            $this->cssStripped,
            'mediacb rule must use the body[class*="fi-panel-admin"] attribute-substring scope.'
        );
    }

    #[Test]
    public function mediacb_block_carries_task_marker(): void
    {
        $this->assertStringContainsString('task-2026-05-30-mediacb', $this->css);
    }

    #[Test]
    public function mediacb_rules_are_scoped_to_admin_panels_not_global(): void
    {
        // Defence: rule must NOT escape the panel scope. Checkout / profile
        // panels keep their own checkbox styles.
        $this->assertDoesNotMatchRegularExpression(
            '/^\s*label\.mw-media-grid-checkbox\s*\{[^}]*min-width:\s*44px/m',
            $this->cssStripped,
            'mw-media-grid-checkbox touch-target rule must NOT exist at unscoped global level.'
        );
    }
}
