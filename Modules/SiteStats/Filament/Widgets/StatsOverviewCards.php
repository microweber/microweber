<?php

namespace Modules\SiteStats\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\SiteStats\Models\Log;
use Modules\SiteStats\Models\Sessions;

class StatsOverviewCards extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        try {
            return $this->buildStats();
        } catch (\Throwable $e) {
            // Guard against missing tables on fresh installs or DB-driver quirks
            return [
                Stat::make('Stats', 'Unavailable')
                    ->description('Stats tables not yet populated')
                    ->color('warning'),
            ];
        }
    }

    protected function buildStats(): array
    {
        // Use an immutable copy so ->subMinutes() etc. never mutate the
        // original — the old code used mutable Carbon::now() and the
        // final $now->subMinutes(5) silently shifted the reference date.
        $now = Carbon::now()->copy();
        $todayViews = Log::whereDate('updated_at', $now->toDateString())->sum('view_count') ?: 0;
        $yesterdayViews = Log::whereDate('updated_at', $now->copy()->subDay()->toDateString())->sum('view_count') ?: 0;

        $todayVisitors = Sessions::whereDate('updated_at', $now->toDateString())->distinct('session_id')->count('session_id') ?: 0;
        $yesterdayVisitors = Sessions::whereDate('updated_at', $now->copy()->subDay()->toDateString())->distinct('session_id')->count('session_id') ?: 0;

        $weekViews = Log::where('updated_at', '>=', $now->copy()->subWeek())->sum('view_count') ?: 0;
        $monthViews = Log::where('updated_at', '>=', $now->copy()->subMonth())->sum('view_count') ?: 0;

        $weekVisitors = Sessions::where('updated_at', '>=', $now->copy()->subWeek())->distinct('session_id')->count('session_id') ?: 0;
        $monthVisitors = Sessions::where('updated_at', '>=', $now->copy()->subMonth())->distinct('session_id')->count('session_id') ?: 0;

        // Use ->copy() to avoid mutating $now (Carbon is mutable)
        $onlineNow = Sessions::where('updated_at', '>=', $now->copy()->subMinutes(5))->distinct('session_id')->count('session_id') ?: 0;

        $stats = [
            Stat::make('Online Now', number_format($onlineNow))
                ->description('Active in last 5 minutes')
                ->descriptionIcon('heroicon-m-signal')
                ->color('success'),

            Stat::make('Today\'s Visitors', number_format($todayVisitors))
                ->description($this->changeDesc($todayVisitors, $yesterdayVisitors, 'vs yesterday'))
                ->descriptionIcon($todayVisitors >= $yesterdayVisitors ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($todayVisitors >= $yesterdayVisitors ? 'success' : 'warning'),

            Stat::make('Today\'s Page Views', number_format($todayViews))
                ->description($this->changeDesc($todayViews, $yesterdayViews, 'vs yesterday'))
                ->descriptionIcon($todayViews >= $yesterdayViews ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($todayViews >= $yesterdayViews ? 'success' : 'warning'),

            Stat::make('This Week', number_format($weekVisitors) . ' visitors')
                ->description(number_format($weekViews) . ' page views')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('This Month', number_format($monthVisitors) . ' visitors')
                ->description(number_format($monthViews) . ' page views')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            $this->getOrdersStat($now),
            $this->getCommentsStat($now),
            $this->getNewsletterStat($now),
        ];

        return array_filter($stats);
    }

    private function getOrdersStat(Carbon $now): ?Stat
    {
        try {
            $monthOrders = \Modules\SiteStats\Models\Orders::where('updated_at', '>=', $now->copy()->subMonth())
                ->where('order_completed', 1)
                ->count();
            return Stat::make('Orders (Month)', number_format($monthOrders))
                ->description('Completed orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary');
        } catch (\Throwable) {
            return null;
        }
    }

    private function getCommentsStat(Carbon $now): ?Stat
    {
        if (!is_module_installed('comments')) {
            return null;
        }
        try {
            $monthComments = \Modules\SiteStats\Models\Comments::where('updated_at', '>=', $now->copy()->subMonth())
                ->count();
            return Stat::make('Comments (Month)', number_format($monthComments))
                ->description('User comments')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('primary');
        } catch (\Throwable) {
            return null;
        }
    }

    private function getNewsletterStat(Carbon $now): ?Stat
    {
        if (!is_module_installed('newsletter')) {
            return null;
        }
        try {
            $totalSubscribers = \Modules\Newsletter\Models\NewsletterSubscriber::count();
            $newThisMonth = \Modules\Newsletter\Models\NewsletterSubscriber::where('created_at', '>=', $now->copy()->subMonth())->count();
            return Stat::make('Subscribers', number_format($totalSubscribers))
                ->description($newThisMonth . ' new this month')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('primary');
        } catch (\Throwable) {
            return null;
        }
    }

    private function changeDesc(float $current, float $previous, string $label): string
    {
        if ($previous == 0) {
            return $current > 0 ? "up 100% $label" : "no change $label";
        }
        $change = (($current - $previous) / $previous) * 100;
        $dir = $change >= 0 ? 'up' : 'down';
        return sprintf('%s %.1f%% %s', $dir, abs($change), $label);
    }
}
