<?php

namespace Modules\Billing\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Modules\Billing\Filament\Admin\Widgets\LatestSubscriptionsWidget;
use Modules\Billing\Filament\Admin\Widgets\StatsOverviewWidget;

class Dashboard extends BaseDashboard
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';

    protected static ?int $navigationSort = 1;

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            LatestSubscriptionsWidget::class,
        ];
    }
}
