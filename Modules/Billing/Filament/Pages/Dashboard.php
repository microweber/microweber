<?php

namespace Modules\Billing\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;
use Modules\Billing\Filament\Widgets\StatsOverviewWidget;
use Modules\Billing\Filament\Widgets\LatestSubscriptionsWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?int $navigationSort = 1;

}
