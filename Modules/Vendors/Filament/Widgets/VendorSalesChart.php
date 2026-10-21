<?php

namespace Modules\Vendors\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Modules\Vendors\Services\VendorStats;

/**
 * Paid revenue per day over the last 30 days for the signed-in vendor.
 */
class VendorSalesChart extends ChartWidget
{
    protected ?string $heading = 'Revenue (last 30 days)';

    protected static ?int $sort = 1;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'min' => 0,
                    'ticks' => ['precision' => 0],
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        $series = VendorStats::for((int) Auth::id())->revenueByDay(30);

        return [
            'datasets' => [
                [
                    'label' => __('Revenue'),
                    'data' => array_values($series),
                    'borderColor' => '#0d6efd',
                    'backgroundColor' => 'rgba(13, 110, 253, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => array_map(fn ($d) => date('M j', strtotime($d)), array_keys($series)),
        ];
    }
}
