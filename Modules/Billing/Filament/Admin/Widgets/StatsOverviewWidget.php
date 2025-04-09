<?php

namespace Modules\Billing\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Billing\Models\Subscription;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Subscriptions', function() {
                return Subscription::query()->count() ?? 0;
            })
                ->description('Total number of subscriptions')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Active Subscriptions', function() {
                return Subscription::query()->where('stripe_status', 'active')->count() ?? 0;
            })
                ->description('Currently active subscriptions')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

//            Stat::make('Revenue This Month', function () {
//                return '$' . number_format(
//                    Subscription::query()
//                        ->where('stripe_status', 'active')
//                        ->whereMonth('created_at', Carbon::now()->month)
//                        ->sum('amount') ?? 0,
//                    2
//                );
//            })
//                ->description('Monthly recurring revenue')
//                ->descriptionIcon('heroicon-m-currency-dollar')
//                ->color('success'),
        ];
    }
}
