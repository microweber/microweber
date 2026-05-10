<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-172 / AI-227 follow-up (2026-05-10) — admin card-layout
 * row action anchors.
 *
 * agent-test verified cycle-169 AI-227 PASS for the table-header
 * Reorder/Filter/Column-manager buttons but flagged 25 per-row
 * Actions still failing at 36×24 / 30×18.
 *
 * Investigation: those are NOT inside `.fi-ta-row` containers —
 * /admin/posts (and /admin/products, /admin/orders) uses a
 * card-grid layout where each row is a `.fi-ta-split` div, not
 * a `<tr class="fi-ta-row">`. The View/Live edit/Edit controls
 * are `<a aria-label="…">` anchors styled with Tailwind text-gray
 * classes — no `.fi-ta-row td a` selector reaches them, so
 * cycle-168's anchor floor missed them.
 *
 * Verified at /admin/posts 390×844:
 *   View      36×24 (was)  → 44×44 (now)
 *   Live edit 30×18 (was)  → 44×44 (now)
 *   Edit      30×18 (was)  → 44×44 (now)
 *
 * Cycle-172 fix: floor any `<a aria-label>` action anchor inside
 * `.fi-ta-split` and inside the broader `.fi-ta` container
 * (catches both card-layout and table-layout admin pages).
 * Scoped to `body.fi-panel-admin` and the same admin-mobile
 * media query.
 */
class Ai227RowActionAnchorsCardLayoutContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_172_anchor(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        $this->assertMatchesRegularExpression('/[Cc]ycle-172/', $src,
            'mobile-touch.css MUST carry the cycle-172 anchor.');
        $this->assertStringContainsString('AI-227 follow-up', $src,
            'mobile-touch.css MUST mark the AI-227 follow-up anchor '
            . '(cycle-169 covered table-header buttons; cycle-172 '
            . 'covers per-row card-layout anchors).');
    }

    #[Test]
    public function ai_227_card_layout_action_anchors_floored(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        // The .fi-ta-split selector is what /admin/posts uses for
        // its card-style row layout — must hit min-height 44.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-split\s+a\[aria-label\][\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST floor body.fi-panel-admin '
            . '.fi-ta-split a[aria-label] to min-height:44px '
            . '!important so card-layout admin pages (Posts, '
            . 'Products, Orders) get the row-action floor.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-split\s+a\[aria-label\][\s\S]{0,400}min-width:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST also floor min-width:44px '
            . '!important on the card-layout action anchors.'
        );
        // Broader .fi-ta catchall — defensive duplicate so the
        // rule still bites if Filament restructures the layout.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta\s+a\[aria-label\]:not\(\[aria-label=""\]\)[\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST also floor body.fi-panel-admin '
            . '.fi-ta a[aria-label]:not([aria-label=""]) so any '
            . 'aria-labelled action anchor inside any admin table '
            . 'container meets the floor (defensive).'
        );
    }

    #[Test]
    public function cycle_172_inside_touch_media_query(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');

        $anchorPos = strpos($src, 'cycle-172');
        $this->assertNotFalse($anchorPos, 'cycle-172 anchor must be present.');
        $mediaPos = strpos($src, '@media', $anchorPos);
        $this->assertNotFalse($mediaPos, 'cycle-172 rules MUST sit inside an @media.');
        $mediaLine = substr($src, $mediaPos, 100);
        $this->assertMatchesRegularExpression(
            '/max-width:\s*1023\.98px/',
            $mediaLine,
            'cycle-172 @media MUST include max-width: 1023.98px.'
        );
        $this->assertStringContainsString('pointer: coarse', $mediaLine,
            'cycle-172 @media MUST include (pointer: coarse).');
    }

    #[Test]
    public function built_bundle_carries_card_layout_floor(): void
    {
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built filament-theme bundle missing.");
        }
        $built = file_get_contents($path);

        $this->assertStringContainsString(
            '.fi-panel-admin .fi-ta-split a[aria-label]',
            $built,
            'Built bundle MUST contain the AI-227 follow-up '
            . 'card-layout action-anchor floor rule.'
        );
    }
}
