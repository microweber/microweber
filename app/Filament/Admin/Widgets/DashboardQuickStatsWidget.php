<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;
use Modules\Order\Models\Order;

class DashboardQuickStatsWidget extends Widget
{
    protected static ?int $sort = -1;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.admin.widgets.dashboard-quick-stats-widget';

    protected static bool $isLazy = false;

    public function getStats(): array
    {
        return [
            [
                'label' => 'Emails',
                'value' => $this->getEmailsCount(),
                'icon' => 'heroicon-o-envelope',
                'color' => 'blue',
                'url' => url(mw_admin_prefix_url() . '/contact-form'),
            ],
            [
                'label' => 'Last comments',
                'value' => $this->getCommentsCount(),
                'icon' => 'heroicon-o-chat-bubble-left-right',
                'color' => 'pink',
                'url' => url(mw_admin_prefix_url() . '/comments'),
            ],
            [
                'label' => 'Sales',
                'value' => $this->getSalesTotal(),
                'icon' => 'heroicon-o-currency-dollar',
                'color' => 'green',
                'url' => url(mw_admin_prefix_url() . '/orders'),
            ],
            [
                'label' => 'Recent Orders',
                'value' => $this->getRecentOrdersCount(),
                'icon' => 'heroicon-o-shopping-bag',
                'color' => 'orange',
                'url' => url(mw_admin_prefix_url() . '/orders'),
            ],
        ];
    }

    private function getEmailsCount(): string
    {
        try {
            return (string) DB::table('forms_data')->count();
        } catch (\Throwable $e) {
            return '0';
        }
    }

    private function getCommentsCount(): string
    {
        try {
            return (string) DB::table('comments')->count();
        } catch (\Throwable $e) {
            return '0';
        }
    }

    private function getSalesTotal(): string
    {
        try {
            $total = Order::query()
                ->where('order_completed', 1)
                ->sum('amount');

            return '$ ' . number_format((float) $total, 2);
        } catch (\Throwable $e) {
            return '$ 0.00';
        }
    }

    private function getRecentOrdersCount(): string
    {
        try {
            return (string) Order::query()
                ->where('order_completed', 1)
                ->count();
        } catch (\Throwable $e) {
            return '0';
        }
    }
}
