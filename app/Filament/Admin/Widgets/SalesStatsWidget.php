<?php

namespace App\Filament\Admin\Widgets;

use Carbon\Carbon;
use Filament\Support\Concerns\CanBeLazy;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Modules\Order\Models\Order;

class SalesStatsWidget extends BaseWidget
{
    use CanBeLazy;

    protected static ?int $sort = 2;

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        return [
            $this->getRevenueStat(),
            $this->getOrdersStat(),
            $this->getAverageOrderValueStat(),
            $this->getSalesGrowthStat(),
        ];
    }

    private function getRevenueStat(): Stat
    {
        $todayRevenue = $this->getRevenueForPeriod('today');
        $yesterdayRevenue = $this->getRevenueForPeriod('yesterday');
        $change = $this->calculateChange($todayRevenue, $yesterdayRevenue);

        $stat = Stat::make('Revenue', $this->formatCurrency($todayRevenue))
            ->description($this->formatChangeDescription($change, 'vs yesterday'))
            ->descriptionIcon($change >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($this->getChangeColor($change));

        return $stat;
    }

    private function getOrdersStat(): Stat
    {
        $today = $this->getOrdersForPeriod('today');
        $yesterday = $this->getOrdersForPeriod('yesterday');
        $change = $this->calculateChange($today, $yesterday);

        $stat = Stat::make('Orders', number_format($today))
            ->description($this->formatChangeDescription($change, 'vs yesterday'))
            ->descriptionIcon($change >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($this->getChangeColor($change));

        return $stat;
    }

    private function getAverageOrderValueStat(): Stat
    {
        $todayAov = $this->getAverageOrderValueForPeriod('today');
        $monthAov = $this->getAverageOrderValueForPeriod('month');
        $change = $this->calculateChange($todayAov, $monthAov);

        $stat = Stat::make('Avg. Order Value', $this->formatCurrency($todayAov))
            ->description($this->formatChangeDescription($change, 'vs monthly avg'))
            ->descriptionIcon($change >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($this->getChangeColor($change));

        return $stat;
    }

    private function getSalesGrowthStat(): Stat
    {
        $thisMonth = $this->getRevenueForPeriod('this_month');
        $lastMonth = $this->getRevenueForPeriod('last_month');
        $growth = $this->calculateChange($thisMonth, $lastMonth);

        $stat = Stat::make('Monthly Growth', round($growth, 1) . '%')
            ->description('vs last month')
            ->descriptionIcon($growth >= 0 ? 'heroicon-m-chart-bar' : 'heroicon-m-chart-bar')
            ->color($this->getChangeColor($growth));

        return $stat;
    }

    private function getRevenueForPeriod(string $period): float
    {
        $query = Order::query()
            ->where('order_completed', 1)
            ->whereNotNull('amount');

        $query = $this->applyPeriodFilter($query, $period);

        return $query->sum('amount') ?? 0;
    }

    private function getOrdersForPeriod(string $period): int
    {
        $query = Order::query()
            ->where('order_completed', 1);

        $query = $this->applyPeriodFilter($query, $period);

        return $query->count() ?? 0;
    }

    private function getAverageOrderValueForPeriod(string $period): float
    {
        $query = Order::query()
            ->where('order_completed', 1)
            ->whereNotNull('amount');

        $query = $this->applyPeriodFilter($query, $period);

        return $query->avg('amount') ?? 0;
    }

    private function applyPeriodFilter($query, string $period): mixed
    {
        $now = Carbon::now();

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', $now->toDateString());
                break;
            case 'yesterday':
                $query->whereDate('created_at', $now->copy()->subDay()->toDateString());
                break;
            case 'week':
                $query->where('created_at', '>=', $now->copy()->subWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', $now->copy()->subMonth());
                break;
            case 'this_month':
                $query->whereMonth('created_at', $now->month)
                    ->whereYear('created_at', $now->year);
                break;
            case 'last_month':
                $lastMonth = $now->copy()->subMonth();
                $query->whereMonth('created_at', $lastMonth->month)
                    ->whereYear('created_at', $lastMonth->year);
                break;
        }

        return $query;
    }

    private function calculateChange(float $current, float $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return (($current - $previous) / $previous) * 100;
    }

    private function formatChangeDescription(float $change, string $period): string
    {
        $direction = $change >= 0 ? 'up' : 'down';
        $absChange = abs($change);

        return sprintf('%s %.1f%% %s', $direction, $absChange, $period);
    }

    private function getChangeColor(float $change): string
    {
        if ($change >= 10) {
            return 'success';
        } elseif ($change >= 0) {
            return 'primary';
        } elseif ($change >= -10) {
            return 'warning';
        } else {
            return 'danger';
        }
    }

    private function formatCurrency(float $amount): string
    {
        $symbol = $this->getCurrencySymbol();

        if ($amount >= 1000000) {
            return $symbol . number_format($amount / 1000000, 1) . 'M';
        } elseif ($amount >= 1000) {
            return $symbol . number_format($amount / 1000, 1) . 'K';
        }

        return $symbol . number_format($amount, 2);
    }

    // task-2026-05-27-13983e / AI-1133 sub-issue 3
    private function getCurrencySymbol(): string
    {
        try {
            return function_exists('currency_symbol')
                ? (currency_symbol() ?: '$')
                : (get_option('currency_symbol', 'website') ?: '$');
        } catch (\Throwable $e) {
            return '$';
        }
    }
}
