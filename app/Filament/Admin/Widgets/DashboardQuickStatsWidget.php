<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Order\Models\Order;

class DashboardQuickStatsWidget extends Widget
{
    protected static ?int $sort = -1;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.admin.widgets.dashboard-quick-stats-widget';

    public function getStats(): array
    {
        $stats = Cache::remember('dashboard_quick_stats', 120, function () {
            $orderStats = $this->getOrderStats();

            return [
                'emails' => $this->getEmailsCount(),
                'comments' => $this->getCommentsCount(),
                'salesTotal' => $orderStats['total'],
                'ordersCount' => $orderStats['count'],
            ];
        });

        $currencySymbol = $this->getCurrencySymbol();

        return [
            [
                'label' => 'Emails',
                'value' => $stats['emails'],
                'icon' => 'heroicon-o-envelope',
                'color' => 'blue',
                'url' => url(mw_admin_prefix_url() . '/contact-form'),
            ],
            [
                'label' => 'Last comments',
                'value' => $stats['comments'],
                'icon' => 'heroicon-o-chat-bubble-left-right',
                'color' => 'pink',
                'url' => url(mw_admin_prefix_url() . '/comments'),
            ],
            [
                'label' => 'Sales',
                'value' => $currencySymbol . ' ' . number_format((float) $stats['salesTotal'], 2),
                'icon' => 'heroicon-o-currency-dollar',
                'color' => 'green',
                'url' => url(mw_admin_prefix_url() . '/orders'),
            ],
            [
                'label' => 'Recent Orders',
                'value' => $stats['ordersCount'],
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

    private function getOrderStats(): array
    {
        try {
            $result = Order::query()
                ->where('order_completed', 1)
                ->selectRaw('COUNT(*) as orders_count, COALESCE(SUM(amount), 0) as orders_total')
                ->first();

            return [
                'count' => (string) ($result->orders_count ?? 0),
                'total' => (float) ($result->orders_total ?? 0),
            ];
        } catch (\Throwable $e) {
            return ['count' => '0', 'total' => 0.0];
        }
    }

    private function getCurrencySymbol(): string
    {
        try {
            return get_option('currency_symbol', 'website') ?: '$';
        } catch (\Throwable $e) {
            return '$';
        }
    }
}
