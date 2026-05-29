<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-27-h3r0a8 — Dashboard hero-stats widget mobile audit
 * (Batch 2). The Daily / Weekly / Monthly / Yearly tab pills and the
 * "View all" link rendered below the WCAG 2.5.5 / iOS HIG 44 by 44
 * touch-target floor on a 390 by 844 viewport (tabs roughly 53-70 by
 * 29; link roughly 61 by 21).
 *
 * Fix: scoped @media (max-width: 768px) block in
 * packages/microweber-filament-theme/resources/assets/css/microweber/
 * general-styles.css that pins .mw-hero-stat-tab and
 * .mw-hero-stat-link to min-height: 44px with inline-flex centering.
 * Desktop layout untouched.
 *
 * Family: WCAG 2.5.5 mobile touch-target floor — same family as
 * railfit AI-1136 Layer A (44 by 44 button pin on right rail).
 */
class AdminH3r0a8HeroStatsTouchTargetContractTest extends TestCase
{
    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
    }

    #[Test]
    public function task_id_marker_present_for_audit_grep(): void
    {
        $this->assertStringContainsString('task-2026-05-27-h3r0a8', $this->css);
    }

    #[Test]
    public function fix_lives_inside_mobile_media_query(): void
    {
        $start = strpos($this->css, 'task-2026-05-27-h3r0a8');
        $this->assertNotFalse($start, 'h3r0a8 marker must be present');

        $mediaPos = strpos($this->css, '@media', $start);
        $this->assertNotFalse($mediaPos, '@media block must follow the h3r0a8 marker');

        $slice = substr($this->css, $mediaPos, 600);

        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*768px\)/',
            $slice,
            'Hero-stats touch-target fix must be inside @media (max-width: 768px).'
        );
    }

    #[Test]
    public function hero_stat_tab_pinned_at_44px_min_height_with_inline_flex(): void
    {
        $start = strpos($this->css, 'task-2026-05-27-h3r0a8');
        $this->assertNotFalse($start);

        $slice = substr($this->css, $start, 1500);

        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-hero-stat-tab\s*\{[^}]*min-height:\s*44px[^}]*\}/s',
            $slice,
            'Tab buttons must declare min-height: 44px so they meet the WCAG 2.5.5 floor.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-hero-stat-tab\s*\{[^}]*display:\s*inline-flex[^}]*\}/s',
            $slice,
            'Tab buttons must declare display: inline-flex so vertical centering works.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-hero-stat-tab\s*\{[^}]*align-items:\s*center[^}]*\}/s',
            $slice,
            'Tab buttons must declare align-items: center for vertical text centering.'
        );
    }

    #[Test]
    public function hero_stat_link_pinned_at_44px_min_height_with_inline_flex(): void
    {
        $start = strpos($this->css, 'task-2026-05-27-h3r0a8');
        $this->assertNotFalse($start);

        $slice = substr($this->css, $start, 1500);

        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-hero-stat-link\s*\{[^}]*min-height:\s*44px[^}]*\}/s',
            $slice,
            'View-all link must declare min-height: 44px so it meets the WCAG 2.5.5 floor.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-hero-stat-link\s*\{[^}]*display:\s*inline-flex[^}]*\}/s',
            $slice,
            'View-all link must declare display: inline-flex.'
        );
    }

    #[Test]
    public function desktop_hero_stat_tab_kept_compact(): void
    {
        // Regression guard: outside the mobile @media block the tab
        // rail must keep its dense 4px / 13px sizing so desktop
        // layout is not bloated by the mobile pin.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-hero-stat-tab\s*\{\s*padding:\s*4px\s+12px;\s*font-size:\s*13px/s',
            $this->css,
            'Desktop .mw-hero-stat-tab rule must keep the dense 4px 12px / 13px sizing.'
        );
    }
}
