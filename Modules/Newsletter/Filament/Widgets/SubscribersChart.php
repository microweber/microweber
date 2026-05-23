<?php

namespace Modules\Newsletter\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Support\Concerns\CanBeLazy;
use Modules\Newsletter\Models\NewsletterSubscriber;

class SubscribersChart extends ChartWidget
{
    use CanBeLazy;

    protected ?string $heading = 'Subscribers';

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    // task-2026-05-23-b59ecf / AI-1056 — clamp Y-axis minimum to 0.
    // Chart.js auto-scales to -1..1 when all data points are 0, making
    // an empty subscriber chart show negative counts which is nonsensical.
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
        $startDate = Carbon::now()->subMonths(6);
        $endDate = Carbon::now()->endOfDay();

        $monthsArray = [];
        foreach ($startDate->toPeriod($endDate, '1 month') as $date) {
            $monthsArray[] = [
                'start' => $date->startOfMonth()->startOfDay()->format('Y-m-d'),
                'end' => $date->endOfMonth()->endOfDay()->format('Y-m-d')
            ];
        }

        $datesHumanFormated = [];
        $subscribersCountByMonths = [];
        foreach ($monthsArray as $date) {
            $dateHuman = Carbon::parse($date['start'])->format('M');
            $subscribersCountByMonths[$dateHuman] = NewsletterSubscriber::whereBetween('created_at', [$date['start'],$date['end']])->count();
            $datesHumanFormated[] = $dateHuman;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Subscribers',
                    'data' => $subscribersCountByMonths,
                    'fill' => 'start',
                ],
            ],
            'labels' => $datesHumanFormated,
        ];
    }
}
