<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Order\Models\Order;

class DashboardQuickStatsWidget extends Widget
{
    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.admin.widgets.dashboard-quick-stats-widget';

    public function getHeroStat(): array
    {
        $currencySymbol = $this->getCurrencySymbol();

        $ranges = Cache::remember('dashboard_hero_sales', 120, function () {
            return [
                'daily' => $this->getSalesForPeriod(1),
                'weekly' => $this->getSalesForPeriod(7),
                'monthly' => $this->getSalesForPeriod(30),
                'yearly' => $this->getSalesForPeriod(365),
            ];
        });

        $formatted = [];
        foreach ($ranges as $key => $data) {
            $formatted[$key] = [
                'total' => $currencySymbol . ' ' . number_format((float) $data['total'], 2),
                'count' => $data['count'],
            ];
        }

        return [
            'label' => 'Sales',
            'icon' => 'heroicon-o-currency-dollar',
            'color' => 'green',
            'url' => url(mw_admin_prefix_url() . '/orders'),
            'ranges' => $formatted,
        ];
    }

    public function getStats(): array
    {
        $stats = Cache::remember('dashboard_quick_stats', 120, function () {
            $sales = $this->getSalesForPeriod(30);
            return [
                'emails' => $this->getEmailsCount(),
                'comments' => $this->getCommentsCount(),
                'salesCount' => $sales['count'],
                'ordersCount' => $this->getOrdersCount(),
            ];
        });

        return [
            [
                'label' => 'Emails',
                'value' => $stats['emails'],
                'icon' => 'heroicon-o-envelope',
                'color' => 'blue',
                'url' => url(mw_admin_prefix_url() . '/form-entries'),
            ],
            [
                'label' => 'Last comments (30 days)',
                'value' => $stats['comments'],
                'icon' => 'heroicon-o-chat-bubble-left-right',
                'color' => 'pink',
                // task-2026-05-21-0e7bf0 / AI-869 — fixed broken URL;
                // /settings/comments (AI-738) returned HTTP 404.
                // Correct route is /admin/comments (HTTP 200).
                'url' => url(mw_admin_prefix_url() . '/comments'),
            ],
            [
                'label' => 'Sales',
                'value' => $stats['salesCount'],
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
            return (string) DB::table('comments')
                ->where('created_at', '>=', now()->subDays(30))
                ->where('comment_email', 'NOT LIKE', '%@example.com')
                ->where('comment_email', 'NOT LIKE', '%@example.org')
                ->where('comment_email', 'NOT LIKE', '%@example.net')
                ->count();
        } catch (\Throwable $e) {
            return '0';
        }
    }

    private function getSalesForPeriod(int $days): array
    {
        try {
            $result = Order::query()
                ->where('order_completed', 1)
                ->where('created_at', '>=', now()->subDays($days))
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

    private function getOrdersCount(): string
    {
        try {
            return (string) Order::query()->where('order_completed', 1)->count();
        } catch (\Throwable $e) {
            return '0';
        }
    }

    private function getCurrencySymbol(): string
    {
        try {
            return function_exists('currency_symbol')
                ? (currency_symbol() ?: '$')
                : (get_option('currency_symbol', 'website') ?: '$');
        } catch (\Throwable $e) {
            return '$';
        }
    }
}
