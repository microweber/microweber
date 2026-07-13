<?php

namespace App\Filament\Admin\Widgets;

use Carbon\Carbon;
use Filament\Support\Concerns\CanBeLazy;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Modules\Order\Models\Order;
use Modules\SiteStats\Models\Log;
use Modules\SiteStats\Models\Sessions;

class TrafficChartWidget extends ChartWidget
{
    use CanBeLazy;

    protected static ?int $sort = 4;

    protected ?string $heading = 'Traffic & Sales Trend';

    protected ?string $pollingInterval = '120s';

    protected int|string|array $columnSpan = 'full';

    /**
     * AI-706 / task-2026-05-16-5cc439 — hero chart 2× current height
     * per designer spec admin-shell-improvements §2 AD5. Filament's
     * default ChartWidget canvas height is governed by Chart.js's
     * 2:1 aspect ratio — typically ~240-280 px on a wide screen.
     * Setting maxHeight='480px' bumps the chart to roughly 2× its
     * previous default, claiming the hero position on the dashboard.
     *
     * The companion "KPIs as a left-rail inside this card" part of
     * §2 AD5 is the AI-706a follow-up candidate — requires a custom
     * widget view and KPI computation; out of scope for slice 1.
     */
    protected ?string $maxHeight = '480px';

    public ?string $filter = '7days';

    protected function getFilters(): ?array
    {
        return [
            '7days' => 'Last 7 Days',
            '30days' => 'Last 30 Days',
            '90days' => 'Last 90 Days',
            'year' => 'This Year',
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        try {
            $data = $this->getTrendData();
        } catch (\Throwable $e) {
            // Guard against missing tables on fresh installs
            return ['datasets' => [], 'labels' => []];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Page Views',
                    'data' => $data['pageViews'],
                    'borderColor' => '#0ea5e9',
                    'backgroundColor' => 'rgba(14, 165, 233, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Unique Visitors',
                    'data' => $data['visitors'],
                    'borderColor' => '#8b5cf6',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Orders',
                    'data' => $data['orders'],
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => false,
                    'borderDash' => [5, 5],
                    'tension' => 0.4,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => 'Traffic',
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Orders',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'interaction' => [
                'mode' => 'nearest',
                'axis' => 'x',
                'intersect' => false,
            ],
        ];
    }

    private function getTrendData(): array
    {
        $now = Carbon::now();

        switch ($this->filter) {
            case '7days':
                $days = 7;
                $format = 'D';
                break;
            case '30days':
                $days = 30;
                $format = 'j M';
                break;
            case '90days':
                $days = 90;
                $format = 'j M';
                break;
            case 'year':
                $days = 365;
                $format = 'M';
                break;
            default:
                $days = 7;
                $format = 'D';
        }

        $labels = [];
        $pageViews = [];
        $visitors = [];
        $orders = [];

        if ($days <= 30) {
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = $now->copy()->subDays($i);
                $dateString = $date->toDateString();

                $labels[] = $date->format($format);

                $pageViews[] = Log::query()
                    ->whereDate('updated_at', $dateString)
                    ->sum('view_count') ?? 0;

                $visitors[] = Sessions::query()
                    ->whereDate('created_at', $dateString)
                    ->distinct('session_id')
                    ->count('session_id') ?? 0;

                $orders[] = Order::query()
                    ->where('order_completed', 1)
                    ->whereDate('created_at', $dateString)
                    ->count() ?? 0;
            }
        } else {
            $interval = ceil($days / 30);

            for ($i = $days; $i >= 0; $i -= $interval) {
                $startDate = $now->copy()->subDays($i);
                $endDate = $now->copy()->subDays(max(0, $i - $interval + 1));

                $labels[] = $startDate->format($format);

                $pageViews[] = Log::query()
                    ->whereBetween('updated_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                    ->sum('view_count') ?? 0;

                $visitors[] = Sessions::query()
                    ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                    ->distinct('session_id')
                    ->count('session_id') ?? 0;

                $orders[] = Order::query()
                    ->where('order_completed', 1)
                    ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                    ->count() ?? 0;
            }
        }

        return [
            'labels' => $labels,
            'pageViews' => $pageViews,
            'visitors' => $visitors,
            'orders' => $orders,
        ];
    }
}
