<?php

namespace Modules\SiteStats\Filament\Widgets;

use Carbon\CarbonImmutable;
use Filament\Widgets\ChartWidget;
use Modules\SiteStats\Repositories\SiteStatsRepository;

class VisitorsChartWidget extends ChartWidget
{
    protected ?string $heading = 'Visitors & Bounce Rate';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '350px';

    public ?string $filter = 'daily';

    protected function getFilters(): ?array
    {
        return [
            'daily' => 'Daily (30 days)',
            'weekly' => 'Weekly (12 weeks)',
            'monthly' => 'Monthly (12 months)',
            'yearly' => 'Yearly (5 years)',
        ];
    }

    protected function getData(): array
    {
        try {
            $period = $this->filter;
            $ranges = [
                'daily' => ['sub' => 'subDays', 'amount' => 30],
                'weekly' => ['sub' => 'subWeeks', 'amount' => 12],
                'monthly' => ['sub' => 'subMonths', 'amount' => 12],
                'yearly' => ['sub' => 'subYears', 'amount' => 5],
            ];

            $cfg = $ranges[$period] ?? $ranges['daily'];
            $startDate = CarbonImmutable::now()->{$cfg['sub']}($cfg['amount']);
            $endDate = CarbonImmutable::now();

            $repo = new SiteStatsRepository();
            $intervals = $repo->getRangesPeriod($startDate, $endDate, $period);
            $visitors = $repo->getSessionsForPeriod($startDate, $endDate, $period);
            $bounced = $repo->getBouncedSessionsForPeriod($startDate, $endDate, $period);

            return [
                'datasets' => [
                    [
                        'label' => 'Visitors',
                        'data' => array_values(array_map('floatval', $visitors)),
                        'borderColor' => '#4299e1',
                        'backgroundColor' => 'rgba(66, 153, 225, 0.1)',
                        'fill' => true,
                    ],
                    [
                        'label' => 'Bounced',
                        'data' => array_values(array_map('floatval', $bounced)),
                        'borderColor' => '#f56565',
                        'backgroundColor' => 'rgba(245, 101, 101, 0.1)',
                        'borderDash' => [5, 5],
                        'fill' => true,
                    ],
                ],
                'labels' => array_values(array_keys($intervals)),
            ];
        } catch (\Throwable $e) {
            return ['datasets' => [], 'labels' => []];
        }
    }

    protected function getType(): string
    {
        return 'line';
    }
}
