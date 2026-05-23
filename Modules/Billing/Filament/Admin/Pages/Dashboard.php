<?php

namespace Modules\Billing\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Modules\Billing\Filament\Admin\Widgets\LatestSubscriptionsWidget;
use Modules\Billing\Filament\Admin\Widgets\StatsOverviewWidget;

class Dashboard extends BaseDashboard
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';

    protected static ?int $navigationSort = 1;

    // task-2026-05-23-6a8f2f / AI-1057 — override BaseDashboard title/heading so the page
    // is clearly identified as "Billing" not the generic "Dashboard" which made it appear
    // to silently redirect to the main admin dashboard.
    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Billing';
    }

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Billing';
    }

    public function getSubheading(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        return 'Manage subscriptions, plans and billing.';
    }

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            LatestSubscriptionsWidget::class,
        ];
    }
}
