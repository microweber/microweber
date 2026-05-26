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
                'url' => url(mw_admin_prefix_url() . '/form-entries'),
            ],
            // task-2026-05-17-18be49 / AI-738: comments stat label
            // carries a clear time scope ("Last comments (30 days)")
            // so users understand the count's meaning. The 30-day
            // window matches getCommentsCount() below.
            //
            // task-2026-05-21-0e7bf0 / AI-869: URL corrected from
            // /admin/settings/comments (404) to /admin/comments (200).
            // /admin/settings/comments does not exist as a route.
            // /admin/comments lists all comments (140 rows confirmed).
            [
                'label' => 'Last comments (30 days)',
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
        // task-2026-05-17-18be49 / AI-738: 30-day window so the
        // "Last comments (30 days)" label is truthful. Pre-fix
        // this returned all-time count paired with the "Last
        // comments" label — designer flagged the mismatch.
        // task-2026-05-26 / AI-1107 — exclude PHPUnit test comments
        // (Faker generates @example.com / @example.org / @example.net emails).
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
