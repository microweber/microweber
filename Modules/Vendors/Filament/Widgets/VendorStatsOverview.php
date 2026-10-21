<?php

namespace Modules\Vendors\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Modules\Vendors\Services\VendorStats;

/**
 * Headline numbers for the signed-in vendor: catalogue size, orders and
 * paid revenue. Scoped to Auth::id() via VendorStats.
 */
class VendorStatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getColumns(): int | array
    {
        return ['@xl' => 4, '!@lg' => 1];
    }

    protected function getStats(): array
    {
        $stats = VendorStats::for((int) Auth::id());
        $spark = array_values($stats->revenueByDay(14));

        $money = fn (float $v) => function_exists('currency_format') ? currency_format($v) : number_format($v, 2);

        return [
            Stat::make(__('Products'), (string) $stats->productCount())
                ->description($stats->activeProductCount() . ' ' . __('active'))
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make(__('Orders'), (string) $stats->orderCount())
                ->description($stats->paidOrderCount() . ' ' . __('paid'))
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color('info'),

            Stat::make(__('Units sold'), (string) $stats->unitsSold())
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),

            Stat::make(__('Revenue'), $money($stats->revenue()))
                ->description(__('Paid, last 14 days trend'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart($spark)
                ->color($stats->revenue() > 0 ? 'success' : 'gray'),
        ];
    }
}
