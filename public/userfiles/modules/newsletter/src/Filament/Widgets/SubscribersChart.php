<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;

class SubscribersChart extends ChartWidget
{
    protected static ?string $heading = 'Subscribers';

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
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
