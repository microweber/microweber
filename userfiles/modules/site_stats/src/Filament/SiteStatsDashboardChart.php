<?php

namespace MicroweberPackages\SiteStats\Filament;


use Filament\Pages\Dashboard\Concerns\HasFilters;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Widgets\LineChartWidget;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Carbon\CarbonImmutable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use MicroweberPackages\Content\Models\Content;


class SiteStatsDashboardChart extends LineChartWidget
{
    use InteractsWithPageFilters;


    protected static ?string $maxHeight = '200px';
    protected static ?int $sort = 2;


    public function getHeading(): string
    {
        return 'Test';
    }

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        $records = [];

        for ($month = 1; $month <= 12; $month++) {
            $records[] = Content::query()
                ->when($startDate, fn(Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn(Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                // ->where('loan_status', 'approved')

                ->whereMonth('created_at', $month)
                ->sum('id');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Test',
                    'data' => array_map('floatval', $records),
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];


    }

}
