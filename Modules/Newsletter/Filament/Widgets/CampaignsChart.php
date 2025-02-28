<?php

namespace Modules\Newsletter\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterSubscriber;

class CampaignsChart extends ChartWidget
{
    protected static ?string $heading = 'Campaigns';

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
        $campaignsCountByMonths = [];
        foreach ($monthsArray as $date) {
            $dateHuman = Carbon::parse($date['start'])->format('M');
            $campaignsCountByMonths[$dateHuman] = NewsletterCampaign::whereBetween('created_at', [$date['start'],$date['end']])->count();
            $datesHumanFormated[] = $dateHuman;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Campaigns',
                    'data' => $campaignsCountByMonths,
                    'fill' => 'start',
                ],
            ],
            'labels' => $datesHumanFormated,
        ];
    }
}
