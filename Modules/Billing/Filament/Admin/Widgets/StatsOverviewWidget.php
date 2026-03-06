<?php

namespace Modules\Billing\Filament\Admin\Widgets;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Support\Concerns\CanBeLazy;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionPlan;

class StatsOverviewWidget extends BaseWidget
{
use CanBeLazy;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        return [
            $this->getMrrStat(),
            $this->getActiveSubscriptionsStat(),
            $this->getChurnRateStat(),
            $this->getTotalSubscriptionsStat(),
        ];
    }

    private function getMrrStat(): Stat
    {
        $mrr = $this->calculateMrr();

        $stat = Stat::make('MRR', Number::currency($mrr / 100, 'USD'))
            ->description('Monthly Recurring Revenue')
            ->descriptionIcon('heroicon-m-currency-dollar')
            ->color('success');

        return $stat;
    }

    private function getActiveSubscriptionsStat(): Stat
    {
        $activeCount = Subscription::query()->where('stripe_status', 'active')->count();

        return Stat::make('Active Subscriptions', $activeCount)
            ->description('Currently active subscriptions')
            ->descriptionIcon('heroicon-m-check-circle')
            ->color('success');
    }

    private function getChurnRateStat(): Stat
    {
        $churnRate = $this->calculateChurnRate();

        $stat = Stat::make('Churn Rate', $churnRate . '%')
            ->description('Canceled in last 30 days')
            ->descriptionIcon('heroicon-m-arrow-trending-down');

        if ($churnRate > 10) {
            $stat->color('danger');
        } elseif ($churnRate > 5) {
            $stat->color('warning');
        } else {
            $stat->color('success');
        }

        return $stat;
    }

    private function getTotalSubscriptionsStat(): Stat
    {
        $totalCount = Subscription::query()->count();

        return Stat::make('Total Subscriptions', $totalCount)
            ->description('All-time subscriptions')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('info');
    }

    private function calculateMrr(): int
    {
        $activeSubscriptions = Subscription::query()
            ->where('stripe_status', 'active')
            ->get();

        $mrr = 0;

        foreach ($activeSubscriptions as $subscription) {
            $plan = $subscription->plan;
            if ($plan) {
                $mrr += $plan->price;
            }
        }

        return $mrr;
    }

    private function calculateChurnRate(): float
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $canceledInLast30Days = Subscription::query()
            ->where('stripe_status', 'canceled')
            ->where('updated_at', '>=', $thirtyDaysAgo)
            ->count();

        $totalActiveAtStart = Subscription::query()
            ->where('created_at', '<', $thirtyDaysAgo)
            ->count();

        if ($totalActiveAtStart === 0) {
            return 0.0;
        }

        return round(($canceledInLast30Days / $totalActiveAtStart) * 100, 2);
    }
}
