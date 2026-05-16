<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-0004bc — Site Statistics admin page: chart on top.
 *
 * The Visitors & Bounce-Rate chart answers the high-bandwidth question
 * ("what does the last 30 days look like?") in one glance. The KPI cards
 * underneath are the drill-down. Render order is fixed by the page blade
 * (Modules/SiteStats/resources/views/filament/pages/site-stats-page.blade.php).
 *
 * This test pins the order: VisitorsChartWidget must appear textually
 * BEFORE StatsOverviewCards in the page blade.
 */
class SiteStats0004bcChartOnTopContractTest extends TestCase
{
    private string $blade;
    private int $chartPos;
    private int $cardsPos;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(
            base_path('Modules/SiteStats/resources/views/filament/pages/site-stats-page.blade.php')
        );
        $this->chartPos = (int) strpos($this->blade, 'VisitorsChartWidget::class');
        $this->cardsPos = (int) strpos($this->blade, 'StatsOverviewCards::class');
    }

    #[Test]
    public function both_widgets_remain_in_the_page_blade(): void
    {
        $this->assertGreaterThan(0, $this->chartPos, 'VisitorsChartWidget must still be rendered.');
        $this->assertGreaterThan(0, $this->cardsPos, 'StatsOverviewCards must still be rendered.');
    }

    #[Test]
    public function chart_is_rendered_before_the_overview_cards(): void
    {
        $this->assertLessThan(
            $this->cardsPos,
            $this->chartPos,
            'VisitorsChartWidget must appear above StatsOverviewCards in the page blade.'
        );
    }

    #[Test]
    public function task_id_is_pinned_in_the_blade_comment(): void
    {
        $this->assertStringContainsString('task-2026-05-16-0004bc', $this->blade);
    }

    #[Test]
    public function downstream_widget_order_remains_intact(): void
    {
        // Regression guard — moving the chart up must not disturb the
        // existing two-column grids below.
        $topPagesPos = strpos($this->blade, 'TopPagesWidget::class');
        $referrersPos = strpos($this->blade, 'ReferrersWidget::class');
        $locationsPos = strpos($this->blade, 'LocationsWidget::class');
        $browsersPos = strpos($this->blade, 'BrowsersWidget::class');
        $languagesPos = strpos($this->blade, 'LanguagesWidget::class');
        $recentPos = strpos($this->blade, 'RecentVisitorsWidget::class');

        $this->assertLessThan($topPagesPos, $this->cardsPos, 'StatsOverviewCards must remain above the data tables.');
        $this->assertLessThan($referrersPos, $topPagesPos);
        $this->assertLessThan($locationsPos, $referrersPos);
        $this->assertLessThan($browsersPos, $locationsPos);
        $this->assertLessThan($languagesPos, $browsersPos);
        $this->assertLessThan($recentPos, $languagesPos);
    }
}
