<?php

namespace MicroweberPackages\Monitoring\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use MicroweberPackages\Monitoring\Models\ErrorTracking;
use MicroweberPackages\Monitoring\Services\ErrorTrackingService;

class ErrorStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $errorService = app(ErrorTrackingService::class);
        
        $todayStats = $errorService->getErrorStats('today');
        $weekStats = $errorService->getErrorStats('week');
        
        $unresolvedCount = ErrorTracking::unresolved()->count();
        $criticalCount = ErrorTracking::unresolved()->critical()->count();
        
        return [
            Stat::make('Unresolved Errors', $unresolvedCount)
                ->description('Errors requiring attention')
                ->descriptionIcon('heroicon-m-bug-ant')
                ->color($unresolvedCount > 0 ? 'danger' : 'success')
                ->chart($this->getErrorTrend()),

            Stat::make('Critical Errors', $criticalCount)
                ->description('Critical level issues')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($criticalCount > 0 ? 'danger' : 'success'),

            Stat::make('Today\'s Errors', $todayStats['total'])
                ->description($todayStats['unresolved'] . ' unresolved today')
                ->descriptionIcon('heroicon-m-calendar')
                ->color($todayStats['unresolved'] > 0 ? 'warning' : 'success'),

            Stat::make('This Week', $weekStats['total'])
                ->description($weekStats['unresolved'] . ' unresolved this week')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color($weekStats['unresolved'] > 10 ? 'danger' : 'gray'),
        ];
    }

    /**
     * Get error trend data for the chart.
     */
    protected function getErrorTrend(): array
    {
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $data[] = ErrorTracking::whereDate('created_at', now()->subDays($i))->count();
        }
        
        return $data;
    }
}
