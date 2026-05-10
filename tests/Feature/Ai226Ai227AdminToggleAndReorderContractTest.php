<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-169 / AI-226 + AI-227 (2026-05-10) — admin-mobile touch
 * target floors batch.
 *
 * agent-test's Drunk Designer admin-settings audit identified two
 * additional sub-44 controls in the Filament admin chrome at 390×844:
 *
 *   AI-226: Filament toggle switches (.fi-toggle / .fi-fo-toggle)
 *           measured 44×24 — Filament base sets `1.5rem` (24px)
 *           height. Below WCAG 2.5.5 / iOS HIG 44 floor on the
 *           smaller axis. Affects /admin/settings/general "Online
 *           Shop" + "Maintenance mode" plus all other settings
 *           pages with a toggle.
 *
 *   AI-227: Reorder records icon button (`.fi-icon-btn` with
 *           `wire:click="toggleTableReordering"`) in admin table
 *           headers measured 36×36 — Filament's `.fi-icon-btn`
 *           defaults to `size-9` (36px). Affects /admin/posts and
 *           every other admin table.
 *
 * Cycle-169 fix:
 *
 *   AI-226 — Floor `body.fi-panel-admin .fi-toggle` (and
 *            `.fi-fo-toggle` plus `[role="switch"].fi-toggle`) to
 *            44×44 !important. Width was already 44 from Filament
 *            base; only height is the failing axis.
 *
 *   AI-227 — Floor `body.fi-panel-admin .fi-ta-header-toolbar
 *            .fi-icon-btn` to 44×44 !important so the reorder
 *            trigger AND any sibling table-header icon buttons
 *            (column manager, filter trigger, etc.) meet the floor.
 *            Belt-and-braces: also target `.fi-icon-btn` with
 *            `wire:click` containing "Reorder" or "reorder" so
 *            the rule still bites if Filament restructures the
 *            header toolbar wrapper class.
 */
class Ai226Ai227AdminToggleAndReorderContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_169_anchor(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        $this->assertMatchesRegularExpression('/[Cc]ycle-169/', $src,
            'mobile-touch.css MUST carry the cycle-169 anchor.');
        $this->assertStringContainsString('AI-226', $src,
            'mobile-touch.css MUST carry the AI-226 anchor.');
        $this->assertStringContainsString('AI-227', $src,
            'mobile-touch.css MUST carry the AI-227 anchor.');
    }

    #[Test]
    public function ai_226_toggle_floored_to_44(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        // Floor scoped to body.fi-panel-admin so the rule does not
        // bleed to other Filament panels (checkout, etc.).
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-toggle[\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST floor body.fi-panel-admin .fi-toggle '
            . 'to min-height:44px !important so admin settings toggle '
            . 'switches meet the WCAG 2.5.5 / iOS HIG 44 floor (was 44×24).'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-fo-toggle[\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST floor body.fi-panel-admin .fi-fo-toggle '
            . 'to 44 !important so the Filament Forms toggle wrapper '
            . 'meets the floor too.'
        );
    }

    #[Test]
    public function ai_227_reorder_button_floored_to_44(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        // The reorder-records trigger lives inside the table header
        // toolbar — floor the whole toolbar's icon-buttons.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-header-toolbar\s+\.fi-icon-btn[\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST floor body.fi-panel-admin '
            . '.fi-ta-header-toolbar .fi-icon-btn to 44×44 !important '
            . 'so the Reorder records trigger and sibling table-header '
            . 'icon buttons meet the touch-target floor (was 36×36).'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-header-toolbar\s+\.fi-icon-btn[\s\S]{0,400}min-width:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST floor min-width:44px !important on the '
            . 'admin table-header icon buttons.'
        );
    }

    #[Test]
    public function cycle_169_inside_touch_media_query(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');

        // Cycle-169 rules MUST sit inside an @media block that
        // matches mobile + touch (same media query as cycle-168).
        $anchorPos = strpos($src, 'cycle-169');
        $this->assertNotFalse($anchorPos, 'cycle-169 anchor must be present.');
        $mediaPos = strpos($src, '@media', $anchorPos);
        $this->assertNotFalse($mediaPos, 'cycle-169 rules MUST sit inside an @media.');
        $mediaLine = substr($src, $mediaPos, 100);
        $this->assertMatchesRegularExpression(
            '/max-width:\s*1023\.98px/',
            $mediaLine,
            'cycle-169 @media MUST include max-width: 1023.98px so the '
            . 'rule fires at admin-drawer-collapse breakpoint.'
        );
        $this->assertStringContainsString('pointer: coarse', $mediaLine,
            'cycle-169 @media MUST include (pointer: coarse) so real '
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

        // Functional pin per cycle-142 lesson — load-bearing rules MUST
        // appear in the compiled bundle.
        $this->assertStringContainsString(
            '.fi-panel-admin .fi-toggle',
            $built,
            'Built bundle MUST contain the AI-226 admin toggle floor rule.'
        );
        $this->assertStringContainsString(
            '.fi-panel-admin .fi-ta-header-toolbar .fi-icon-btn',
            $built,
            'Built bundle MUST contain the AI-227 admin table-header '
            . 'icon-button floor rule.'
        );
    }
}
