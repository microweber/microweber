<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-7fceee / AI-895 MEDIUM — Bootstrap pagination .page-link
 * touch-target floor.
 *
 * Tester code-level analysis found .page-link renders at ~38px height by
 * default (6px below WCAG 2.5.5 44×44px floor). Pagination was not triggered
 * at test time (only 6 products), so this fix is preemptive — when content
 * grows and pagination activates, .page-link will already meet the floor.
 *
 * Covers: pagination numbers, prev/next arrows, and ellipsis items.
 */
class Pagination7fceeeAI895PageLinkContractTest extends TestCase
{
    private string $src;
    private string $srcStripped;
    private string $served;

    protected function setUp(): void
    {
        parent::setUp();
        $this->src = (string) file_get_contents(
            base_path('Templates/Bootstrap/resources/assets/css/public-touch.css')
        );
        $this->srcStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->src) ?? $this->src;
        $this->served = (string) file_get_contents(
            base_path('public/templates/bootstrap/css/public-touch.css')
        );
    }

    // ─── Rule presence ────────────────────────────────────────────────────────

    #[Test]
    public function page_link_gets_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.page-link\s*\{[^}]*min-height:\s*44px~s',
            $this->src,
            '.page-link must have min-height: 44px.'
        );
    }

    #[Test]
    public function page_link_gets_min_width_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.page-link\s*\{[^}]*min-width:\s*44px~s',
            $this->src,
            '.page-link must have min-width: 44px.'
        );
    }

    #[Test]
    public function page_link_uses_flex_centering(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.page-link\s*\{[^}]*display:\s*inline-flex~s',
            $this->src,
            '.page-link must use display:inline-flex for vertical centering of text/icons.'
        );
    }

    // ─── Inside touch @media block ────────────────────────────────────────────

    #[Test]
    public function page_link_rule_inside_standard_touch_media_query(): void
    {
        $markerPos = strrpos($this->src, 'task-2026-05-22-7fceee');
        $this->assertNotFalse($markerPos, 'task-2026-05-22-7fceee marker must exist.');

        $slice = substr($this->src, $markerPos, 1800);
        $this->assertStringContainsString(
            '@media (max-width: 1023.98px), (hover: none) and (pointer: coarse)',
            $slice,
            '.page-link touch-target fix must be inside the standard WCAG touch-viewport @media query.'
        );
    }

    // ─── Regression guards ────────────────────────────────────────────────────

    #[Test]
    public function swiper_pagination_bullets_not_affected(): void
    {
        // .swiper-pagination-bullet is a different pagination surface (Swiper.js)
        // that has its own size rules. AI-895 must not change those.
        $this->assertStringContainsString(
            '.swiper-pagination-bullet',
            $this->srcStripped,
            '.swiper-pagination-bullet rules must still be present — separate surface from Bootstrap pagination.'
        );
    }

    #[Test]
    public function ai883_shop_rules_still_present(): void
    {
        $this->assertStringContainsString(
            '.mw-shop-filter-categories',
            $this->srcStripped,
            'AI-883 shop category filter rule must still be present.'
        );
    }

    #[Test]
    public function ai877_bare_link_color_still_present(): void
    {
        $this->assertStringContainsString(
            'a:not(.btn):not(.navbar-brand)',
            $this->srcStripped,
            'AI-877 bare-link color override must still be present.'
        );
    }

    // ─── Source + served mirror parity ───────────────────────────────────────

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-7fceee',
            $this->src,
            'public-touch.css must carry the task-2026-05-22-7fceee marker.'
        );
    }

    #[Test]
    public function source_and_served_mirror_are_byte_identical(): void
    {
        $this->assertSame(
            $this->src,
            $this->served,
            'Templates/Bootstrap/.../public-touch.css and public/.../public-touch.css must be byte-identical.'
        );
    }
}
