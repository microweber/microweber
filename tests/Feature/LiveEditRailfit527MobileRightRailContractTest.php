<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-27-railfit + task-2026-05-27-d4r1l0 — Live Edit right
 * vertical sidebar rail on mobile (≤768px) was 40px wide, but each
 * button inside the rail was 44px (WCAG 2.5.5 touch-target floor).
 * Buttons therefore overflowed the rail by 4px to the right, pushing
 * past the 390px viewport edge (x=350..394). Simultaneously, the
 * canvas frame holder's right offset was still 40px so the rightmost
 * 8px of the canvas ghosted under the widened rail.
 *
 * Coupled two-layer fix (rail-width + frame-holder-offset):
 *
 * Layer A (live-edit-mobile.css, task-2026-05-27-railfit):
 *   - Rail width 40px → 48px at 4-class specificity prefix
 *     `body.fi-panel-admin .mw-live-edit-page .mw-le-right-sidebar`
 *     so it beats the frontend-assets bundle rule at equal specificity
 *     via source-order tiebreak failing prior.
 *   - Buttons pinned at 44×44 (width + min-width + max-width +
 *     min-height) so the WCAG floor is held inside the wider rail.
 *
 * Layer B (live-edit-classes.css, task-2026-05-27-d4r1l0):
 *   - #live-edit-frame-holder { right: 40px → 48px !important; } so
 *     the canvas frame holder right edge matches the new rail width.
 *
 * Stage-2 cascade-loss family — Sub-variant: layout-budget-token
 * two-layer defence. Layer A alone leaves canvas hidden under widened
 * rail; Layer B alone leaves buttons overflowing past viewport. Both
 * required.
 *
 * Tier-3 runtime probe before this test was written:
 *   frameHolder.right=342, sidebar.left=342, overlap=0,
 *   computedRight="48px", bodyOverflow=0.
 */
class LiveEditRailfit527MobileRightRailContractTest extends TestCase
{
    private string $mobileCss;
    private string $classesCss;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mobileCss = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css'
        ));
        $this->classesCss = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css'
        ));
    }

    #[Test]
    public function layer_a_rail_widened_to_48px_at_4_class_specificity(): void
    {
        // Updated 2026-06: the rule block now also carries an
        // `margin-right: 6px !important` inset (task-2026-06-04-le834),
        // so the block is no longer width-only. Match the `width: 48px
        // !important` declaration anywhere inside the block rather than
        // requiring it to be the sole declaration.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-live-edit-page\s+\.mw-live-edit-right-sidebar-template-sidebar[^{]*\{[^}]*width:\s*48px\s*!important/s',
            $this->mobileCss,
            'Right sidebar rail must be widened to 48px at `body.fi-panel-admin` '
            . '4-class specificity prefix so it beats the frontend-assets bundle '
            . 'default rule on mobile.'
        );
    }

    #[Test]
    public function layer_a_rail_buttons_pinned_at_44_wcag_touch_target(): void
    {
        // Must pin width + min-width + max-width + min-height to 44px so
        // browsers cannot squeeze the button below the WCAG 2.5.5 floor
        // when flex parent re-distributes space.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin[^{]*\.btn-icon\.live-edit-toolbar-buttons[^{]*\{[^}]*width:\s*44px\s*!important/s',
            $this->mobileCss,
            'Rail buttons must declare `width: 44px !important`.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin[^{]*\.btn-icon\.live-edit-toolbar-buttons[^{]*\{[^}]*min-width:\s*44px\s*!important/s',
            $this->mobileCss,
            'Rail buttons must declare `min-width: 44px !important` to hold WCAG floor under flex re-distribution.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin[^{]*\.btn-icon\.live-edit-toolbar-buttons[^{]*\{[^}]*max-width:\s*44px\s*!important/s',
            $this->mobileCss,
            'Rail buttons must declare `max-width: 44px !important` so they cannot bleed past 48px rail.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin[^{]*\.btn-icon\.live-edit-toolbar-buttons[^{]*\{[^}]*min-height:\s*44px\s*!important/s',
            $this->mobileCss,
            'Rail buttons must declare `min-height: 44px !important` (WCAG 2.5.5 vertical floor).'
        );
    }

    #[Test]
    public function layer_a_rule_lives_inside_mobile_media_query(): void
    {
        // The rail-widen rule must be scoped to ≤768px so desktop is
        // unaffected. Slice from the second mobile @media block
        // (the one carrying the railfit marker).
        $start = strpos($this->mobileCss, 'task-2026-05-27-railfit');
        $this->assertNotFalse($start, 'railfit marker must be present');

        // Walk back to find the enclosing @media boundary.
        $mediaPos = strrpos(substr($this->mobileCss, 0, $start), '@media');
        $this->assertNotFalse($mediaPos);
        $mediaLine = substr($this->mobileCss, $mediaPos, 200);
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*768px\)/',
            $mediaLine,
            'railfit fix must be inside @media (max-width: 768px) block.'
        );
    }

    #[Test]
    public function layer_b_frame_holder_right_offset_bumped_to_48px(): void
    {
        $this->assertMatchesRegularExpression(
            '/#live-edit-frame-holder\s*\{\s*right:\s*48px\s*!important\s*;?\s*\}/s',
            $this->classesCss,
            'Frame holder right offset must be 48px to match the widened rail. '
            . 'A 40px right offset would leave the canvas ghosting 8px under the rail.'
        );
    }

    #[Test]
    public function layer_b_frame_holder_rule_lives_inside_mobile_media_query(): void
    {
        $start = strpos($this->classesCss, 'task-2026-05-27-d4r1l0');
        $this->assertNotFalse($start, 'd4r1l0 marker must be present');

        $mediaPos = strrpos(substr($this->classesCss, 0, $start), '@media');
        $this->assertNotFalse($mediaPos);
        $mediaLine = substr($this->classesCss, $mediaPos, 200);
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*767\.98px\)/',
            $mediaLine,
            'Frame holder right-offset bump must be inside @media (max-width: 767.98px) block.'
        );
    }

    #[Test]
    public function task_id_markers_present_for_audit_grep(): void
    {
        $this->assertStringContainsString('task-2026-05-27-railfit', $this->mobileCss);
        $this->assertStringContainsString('task-2026-05-27-d4r1l0', $this->classesCss);
    }
}
