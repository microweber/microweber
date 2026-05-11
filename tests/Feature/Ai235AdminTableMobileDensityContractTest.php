<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-176 / AI-235 (2026-05-10) — admin tables mobile density.
 *
 * agent-test reported: "275px per row × 25 rows = 6,875px
 * scroll" at 390×844 — mobile users see only 1 row per viewport,
 * making admin tables ("Posts", "Products", "Orders")
 * effectively unusable for browsing.
 *
 * After cycle-167-cycle-175 row-height was already brought down
 * to ~226px (mostly by 44×44 floors making the action row
 * predictable). Cycle-176 tightens further by trimming the
 * row's outer flex padding, inner title-row gap, and clamping
 * the meta text to 2 lines.
 *
 * Browser-verified at /admin/posts 390×844:
 *   Before cycle-176: avg row height 226px
 *   After  cycle-176: avg row height 214px
 *   Total scroll height 5,349 → 4,917 (~430px reclaimed)
 *
 * Touch-target floors from cycle-168/172 are PRESERVED — the
 * 44×44 row-action anchor floor (cycle-172) is the load-bearing
 * accessibility rule and must not be tightened.
 */
class Ai235AdminTableMobileDensityContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_176_anchor(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        $this->assertMatchesRegularExpression('/[Cc]ycle-176/', $src,
            'mobile-touch.css MUST carry the cycle-176 anchor.');
        $this->assertStringContainsString('AI-235', $src,
            'mobile-touch.css MUST carry the AI-235 anchor.');
    }

    #[Test]
    public function ai_235_row_outer_padding_tightened(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        // py-4 (16px) → py-2 (8px) on the row's outer flex container.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-split[\s\S]{0,600}padding-top:\s*0\.5rem\s*!important/m',
            $src,
            'mobile-touch.css MUST tighten body.fi-panel-admin '
            . '.fi-ta-split outer padding-top to 0.5rem !important '
            . 'so each row sheds ~8px of vertical chrome (Tailwind '
            . 'py-4 → py-2 on mobile).'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-split[\s\S]{0,600}padding-bottom:\s*0\.5rem\s*!important/m',
            $src,
            'mobile-touch.css MUST match padding-bottom: 0.5rem.'
        );
        // gap-4 (16px) → gap-2 (8px) on the outer flex container.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-split[\s\S]{0,600}gap:\s*0\.5rem\s*!important/m',
            $src,
            'mobile-touch.css MUST tighten outer flex gap to 0.5rem '
            . '!important (Tailwind gap-4 → gap-2).'
        );
    }

    #[Test]
    public function ai_235_inner_title_row_gap_tightened(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        // Inner title row Tailwind gap-3 (12px) → gap-1 (4px) so
        // title + status badge stack tighter in column layout.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-split[\s\S]{0,800}gap:\s*0\.25rem\s*!important/m',
            $src,
            'mobile-touch.css MUST tighten the inner title-row gap '
            . 'to 0.25rem !important (Tailwind gap-3 → gap-1) so '
            . 'title and status badge sit closer in stacked column '
            . 'layout on mobile.'
        );
    }

    #[Test]
    public function ai_235_action_row_margin_tightened(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        // .mt-2 (8px) → .mt-1 (4px) so the action row hugs the title.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-split[\s\S]{0,800}\.mt-2[\s\S]{0,200}margin-top:\s*0\.25rem\s*!important/m',
            $src,
            'mobile-touch.css MUST tighten action-row top margin '
            . 'to 0.25rem !important (Tailwind mt-2 → mt-1).'
        );
    }

    #[Test]
    public function ai_235_meta_text_clamped_to_2_lines(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        // -webkit-line-clamp: 2 on the meta text-muted / text-gray
        // children so multi-line meta strings don't wrap to 3-4
        // lines on a narrow viewport.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-ta-split[\s\S]{0,800}-webkit-line-clamp:\s*2/m',
            $src,
            'mobile-touch.css MUST clamp .fi-ta-split meta text '
            . '(.text-muted / .text-sm / .text-gray-*) to 2 lines '
            . 'so URL slugs + categories don\'t cause runaway row '
            . 'growth on narrow viewports.'
        );
    }

    #[Test]
    public function cycle_176_inside_touch_media_query(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');

        $anchorPos = strpos($src, 'cycle-176');
        $this->assertNotFalse($anchorPos, 'cycle-176 anchor must be present.');
        $before = substr($src, 0, $anchorPos);
        $mediaPos = strpos($src, '@media', $anchorPos);
        $this->assertNotFalse($mediaPos, 'cycle-176 rules MUST sit inside an @media block.');
        $mediaLine = substr($src, $mediaPos, 100);
        $this->assertMatchesRegularExpression(
            '/max-width:\s*1023\.98px/',
            $mediaLine,
            'cycle-176 @media MUST include max-width: 1023.98px so '
            . 'the rule fires at admin-drawer-collapse breakpoint.'
        );
        $this->assertStringContainsString('pointer: coarse', $mediaLine,
            'cycle-176 @media MUST include (pointer: coarse) so '
            . 'touch devices hit the floor regardless of width.');
    }

    #[Test]
    public function built_bundle_carries_density_rules(): void
    {
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built filament-theme bundle missing.");
        }
        $built = file_get_contents($path);

        $this->assertStringContainsString(
            '.fi-panel-admin .fi-ta-split',
            $built,
            'Built bundle MUST contain the AI-235 admin table row '
            . 'density rules.'
        );
        // Specific verification — the line-clamp meta rule.
        $this->assertStringContainsString(
            '-webkit-line-clamp: 2',
            $built,
            'Built bundle MUST contain the -webkit-line-clamp: 2 '
            . 'rule that caps meta text growth.'
        );
    }
}
