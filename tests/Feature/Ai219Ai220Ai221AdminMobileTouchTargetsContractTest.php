<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-168 / AI-219 + AI-220 + AI-221 (2026-05-10) — Admin mobile
 * touch-target floors batch.
 *
 * agent-test's AI-217 admin-mobile audit found 3 categories of
 * undersized controls in the Filament admin chrome at 390×844:
 *
 *   AI-219: topbar Add (52×36), Live Edit (56×36), user-menu (32×32)
 *   AI-220: sidebar nav items (167×40-42)
 *   AI-221: table row controls (bulk checkbox, View / Live edit / Edit
 *           anchors, Published button, Actions dropdown)
 *
 * Cycle-168 fix:
 *
 *   AI-219 — `body.fi-panel-admin .fi-topbar .fi-btn` floored to
 *            44×44 with !important (cycle-N TICKET-U covered
 *            `.fi-topbar-nav-button` but the actual Add + Live Edit
 *            buttons are `.fi-btn` Filament Action buttons, not
 *            `.fi-topbar-nav-button`). User-menu wrapper
 *            `.fi-dropdown-trigger` floored too.
 *
 *   AI-220 — Scope decision: NO layout change. Filament already
 *            collapses the sidebar to off-screen at <1024px (drawer
 *            pattern). Sidebar items already ship at 44px tall
 *            (`fi-sidebar-item-btn`). The "extends past screen edge"
 *            measurement is the open-drawer state — that's the
 *            deliberate slide-out pattern, not a bug. No code
 *            change for AI-220; just a regression-guard pin in the
 *            contract test that the TICKET-V row-floor rule is
 *            still present.
 *
 *   AI-221 — Floor every anchor + checkbox-label inside `.fi-ta-row`
 *            scoped under `body.fi-panel-admin`. The cycle-N
 *            TICKET-V floors covered `.fi-ta-actions` descendants
 *            but the View / Live edit / Edit anchor cells live in
 *            separate `<td>` cells outside `.fi-ta-actions` — the
 *            new rule covers them.
 */
class Ai219Ai220Ai221AdminMobileTouchTargetsContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_168_anchor(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        $this->assertMatchesRegularExpression('/[Cc]ycle-168/', $src,
            'mobile-touch.css MUST carry the cycle-168 anchor.');
        $this->assertStringContainsString('AI-219', $src,
            'mobile-touch.css MUST carry the AI-219 anchor.');
        $this->assertStringContainsString('AI-221', $src,
            'mobile-touch.css MUST carry the AI-221 anchor.');
    }

    #[Test]
    public function ai_219_topbar_buttons_floored_to_44(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        // Floor scoped to body.fi-panel-admin so checkout panel stays
        // on its cycle-161/162 floors.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-topbar\s+\.fi-btn[\s\S]{0,500}min-height:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST floor body.fi-panel-admin .fi-topbar '
            . '.fi-btn to min-height:44px !important so admin Add + '
            . 'Live Edit Filament-action buttons meet the floor.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-topbar\s+\.fi-dropdown-trigger[\s\S]{0,500}min-width:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST floor body.fi-panel-admin .fi-topbar '
            . '.fi-dropdown-trigger to 44×44 !important so the user-menu '
            . 'wrapper enlarges (was 0×32).'
        );
    }

    #[Test]
    public function ai_220_existing_sidebar_floor_preserved(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        // Cycle-N TICKET-V row-action floor — must remain intact;
        // cycle-168 only ADDS rules, doesn't remove cycle-N coverage.
        // Pin the original .fi-ta-row .fi-ta-actions rule.
        $this->assertMatchesRegularExpression(
            '/\.fi-ta-row\s+\.fi-ta-actions[\s\S]{0,500}min-height:\s*44px/m',
            $src,
            'mobile-touch.css MUST keep the cycle-N TICKET-V '
            . '.fi-ta-row .fi-ta-actions floor — cycle-168 only ADDS '
            . 'rules.'
        );
    }

    #[Test]
    public function ai_221_table_row_anchors_floored(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        // The View / Live edit / Edit anchors live in custom <td> cells
        // outside .fi-ta-actions. Floor any anchor inside .fi-ta-row.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-row\s+td\s+a[\s\S]{0,400}min-height:\s*44px/m',
            $src,
            'mobile-touch.css MUST floor body.fi-panel-admin .fi-ta-row '
            . 'td a so View / Live edit / Edit anchor cells meet the '
            . 'floor (cycle-N covered .fi-ta-actions descendants but '
            . 'these anchors live in separate <td> cells).'
        );
    }

    #[Test]
    public function ai_221_checkbox_label_44_floor_admin_scoped(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        // Bulk-select checkbox label wrapper — defensive duplicate
        // scoped to admin panel.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-row\s+label:has\(>\s*input\[type="checkbox"\]\)[\s\S]{0,300}min-width:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST floor the body.fi-panel-admin scoped '
            . 'bulk-select checkbox label to 44×44 !important.'
        );
    }

    #[Test]
    public function ai_219_220_221_inside_touch_media_query(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');

        // Cycle-168 rules MUST sit inside an @media block that
        // matches mobile + touch (the 1023.98px breakpoint Filament
        // uses for its drawer collapse + the (hover: none) +
        // (pointer: coarse) for touch devices).
        $anchorPos = strpos($src, 'cycle-168');
        $this->assertNotFalse($anchorPos, 'cycle-168 anchor must be present.');
        $mediaPos = strpos($src, '@media', $anchorPos);
        $this->assertNotFalse($mediaPos, 'cycle-168 rules MUST sit inside an @media.');
        $mediaLine = substr($src, $mediaPos, 100);
        $this->assertMatchesRegularExpression(
            '/max-width:\s*1023\.98px/',
            $mediaLine,
            'cycle-168 @media MUST include max-width: 1023.98px so the '
            . 'rule fires at admin-drawer-collapse breakpoint.'
        );
        $this->assertStringContainsString('pointer: coarse', $mediaLine,
            'cycle-168 @media MUST include (pointer: coarse) so real '
            . 'touch devices hit the floor regardless of width.');
    }

    #[Test]
    public function built_bundle_carries_admin_floors(): void
    {
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built filament-theme bundle missing.");
        }
        $built = file_get_contents($path);

        // Functional pin per cycle-142 lesson.
        $this->assertStringContainsString(
            '.fi-panel-admin .fi-topbar .fi-btn',
            $built,
            'Built bundle MUST contain the AI-219 admin topbar floor '
            . 'rule.'
        );
        $this->assertStringContainsString(
            '.fi-panel-admin .fi-ta-row td a',
            $built,
            'Built bundle MUST contain the AI-221 row-anchor floor '
            . 'rule.'
        );
    }
}
