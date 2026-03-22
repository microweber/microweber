<?php

namespace App\Filament\Admin\Widgets;

use Carbon\Carbon;
use Filament\Support\Concerns\CanBeLazy;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Modules\SiteStats\Models\Log;
use Modules\SiteStats\Models\Sessions;

class TrafficStatsWidget extends BaseWidget
{
    use CanBeLazy;

    protected static ?int $sort = 1;

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        return [
            $this->getPageViewsStat(),
            $this->getUniqueVisitorsStat(),
            $this->getAverageSessionDurationStat(),
            $this->getBounceRateStat(),
        ];
    }

    private function getPageViewsStat(): Stat
    {
        $today = $this->getPageViewsForPeriod('today');
        $yesterday = $this->getPageViewsForPeriod('yesterday');
        $change = $this->calculateChange($today, $yesterday);

        $stat = Stat::make('Page Views', number_format($today))
            ->description($this->formatChangeDescription($change, 'yesterday'))
            ->descriptionIcon($change >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($this->getChangeColor($change));

        return $stat;
    }

    private function getUniqueVisitorsStat(): Stat
    {
        $today = $this->getUniqueVisitorsForPeriod('today');
        $yesterday = $this->getUniqueVisitorsForPeriod('yesterday');
        $change = $this->calculateChange($today, $yesterday);

        $stat = Stat::make('Unique Visitors', number_format($today))
            ->description($this->formatChangeDescription($change, 'yesterday'))
            ->descriptionIcon($change >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($this->getChangeColor($change));

        return $stat;
    }

    private function getAverageSessionDurationStat(): Stat
    {
        $duration = $this->getAverageSessionDuration();
        $formattedDuration = $this->formatDuration($duration);

        return Stat::make('Avg. Session Duration', $formattedDuration)
            ->description('Average time on site')
            ->descriptionIcon('heroicon-m-clock')
            ->color('info');
    }

    private function getBounceRateStat(): Stat
    {
        $rate = $this->getBounceRate();
        $formattedRate = round($rate, 1) . '%';

        $stat = Stat::make('Bounce Rate', $formattedRate)
            ->description('Single page sessions')
            ->descriptionIcon('heroicon-m-arrow-uturn-left');

        if ($rate > 70) {
            $stat->color('danger');
        } elseif ($rate > 50) {
            $stat->color('warning');
        } else {
            $stat->color('success');
        }

        return $stat;
    }

    private function getPageViewsForPeriod(string $period): int
    {
        $query = Log::query();
        $query = $this->applyPeriodFilter($query, $period);

        return $query->sum('view_count') ?? 0;
    }

    private function getUniqueVisitorsForPeriod(string $period): int
    {
        $query = Sessions::query();
        $query = $this->applyPeriodFilter($query, $period);

        return $query->distinct('session_id')->count('session_id') ?? 0;
    }

    private function getAverageSessionDuration(): int
    {
        $sessions = Sessions::query()
            ->where('updated_at', '>=', Carbon::now()->subDay())
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->get();

        if ($sessions->isEmpty()) {
            return 0;
        }

        $totalDuration = 0;
        $count = 0;

        foreach ($sessions as $session) {
            if ($session->created_at && $session->updated_at) {
                $duration = $session->updated_at->diffInSeconds($session->created_at);
                $totalDuration += $duration;
                $count++;
            }
        }

        return $count > 0 ? (int) ($totalDuration / $count) : 0;
    }

    private function getBounceRate(): float
    {
        $sessions = Sessions::query()
            ->where('updated_at', '>=', Carbon::now()->subDay())
            ->count();

        if ($sessions === 0) {
            return 0.0;
        }

        $singlePageSessions = Sessions::query()
            ->where('updated_at', '>=', Carbon::now()->subDay())
            ->whereHas('views', function ($query) {
                $query->havingRaw('COUNT(*) = 1');
            }, '=', 1)
            ->count();

        return ($singlePageSessions / $sessions) * 100;
    }

    private function applyPeriodFilter($query, string $period): mixed
    {
        $now = Carbon::now();

        switch ($period) {
            case 'today':
                $query->whereDate('updated_at', $now->toDateString());
                break;
            case 'yesterday':
                $query->whereDate('updated_at', $now->copy()->subDay()->toDateString());
                break;
            case 'week':
                $query->where('updated_at', '>=', $now->copy()->subWeek());
                break;
            case 'month':
                $query->where('updated_at', '>=', $now->copy()->subMonth());
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

    private function formatDuration(int $seconds): string
    {
        if ($seconds < 60) {
            return $seconds . 's';
        }

        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;

        if ($minutes < 60) {
            return sprintf('%dm %02ds', $minutes, $remainingSeconds);
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        return sprintf('%dh %02dm', $hours, $remainingMinutes);
    }
}
