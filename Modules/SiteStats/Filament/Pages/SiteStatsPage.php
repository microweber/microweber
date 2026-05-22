<?php

namespace Modules\SiteStats\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class SiteStatsPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Site Statistics';

    protected static ?string $title = 'Site Statistics';

    // task-2026-05-22-f9ebf9 / AI-1037 — changed from 'site-stats' to 'site-statistics'
    // so the URL /admin/site-statistics matches the nav label 'Site Statistics'.
    protected static ?string $slug = 'site-statistics';

    protected static string|\UnitEnum|null $navigationGroup = 'Dashboard';

    protected static ?int $navigationSort = 2;

    protected string $view = 'modules.site_stats::filament.pages.site-stats-page';

    public function getHeading(): string|Htmlable
    {
        return 'Site Statistics';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Comprehensive analytics and visitor insights';
    }

    public function getWidgets(): array
    {
        return [
            \Modules\SiteStats\Filament\Widgets\StatsOverviewCards::class,
            \Modules\SiteStats\Filament\Widgets\VisitorsChartWidget::class,
            \Modules\SiteStats\Filament\Widgets\TopPagesWidget::class,
            \Modules\SiteStats\Filament\Widgets\ReferrersWidget::class,
            \Modules\SiteStats\Filament\Widgets\LocationsWidget::class,
            \Modules\SiteStats\Filament\Widgets\BrowsersWidget::class,
            \Modules\SiteStats\Filament\Widgets\LanguagesWidget::class,
            \Modules\SiteStats\Filament\Widgets\RecentVisitorsWidget::class,
        ];
    }

    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    public function getColumns(): int|string|array
    {
        return 2;
    }
}
