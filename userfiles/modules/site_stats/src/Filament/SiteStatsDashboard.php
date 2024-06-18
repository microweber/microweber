<?php

namespace MicroweberPackages\SiteStats\Filament;


use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Pages\Page;
use Filament\Pages\BasePage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use MicroweberPackages\Content\Models\Content;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class SiteStatsDashboard extends BaseWidget
{
     use InteractsWithPageFilters;



    protected function getStats(): array
    {
        return [
            Stat::make('Unique views', '192.1k'),
            Stat::make('Bounce rate', '21%'),
            Stat::make('Average time on page', '3:12'),
            Stat::make('Conversion rate', '3.2%'),

        ];
    }
}
