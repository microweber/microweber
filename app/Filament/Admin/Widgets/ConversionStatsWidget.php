<?php

namespace App\Filament\Admin\Widgets;

use Carbon\Carbon;
use Filament\Support\Concerns\CanBeLazy;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Cart\Models\Cart;
use Modules\Order\Models\Order;
use Modules\SiteStats\Models\Sessions;

class ConversionStatsWidget extends BaseWidget
{
    use CanBeLazy;

    protected static ?int $sort = 3;

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        try {
            return [
                $this->getOverallConversionRateStat(),
                $this->getCartToCheckoutRateStat(),
                $this->getCheckoutToOrderRateStat(),
                $this->getAbandonedCartRateStat(),
            ];
        } catch (\Throwable $e) {
            return [
                Stat::make('Conversion', 'Unavailable')
                    ->description('Stats tables not ready')
                    ->color('warning'),
            ];
        }
    }

    private function getOverallConversionRateStat(): Stat
    {
        $rate = $this->calculateConversionRate();
        $formattedRate = round($rate, 2) . '%';

        $yesterdayRate = $this->calculateConversionRate('yesterday');
        $change = $rate - $yesterdayRate;

        $stat = Stat::make('Overall Conversion Rate', $formattedRate)
            ->description($this->formatChangeDescription($change, 'vs yesterday'))
            ->descriptionIcon($change >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($this->getConversionColor($rate));

        return $stat;
    }

    private function getCartToCheckoutRateStat(): Stat
    {
        $rate = $this->calculateCartToCheckoutRate();
        $formattedRate = round($rate, 1) . '%';

        $stat = Stat::make('Cart → Checkout', $formattedRate)
            ->description('Users who started checkout')
            ->descriptionIcon('heroicon-m-shopping-cart');

        if ($rate < 30) {
            $stat->color('danger');
        } elseif ($rate < 60) {
            $stat->color('warning');
        } else {
            $stat->color('success');
        }

        return $stat;
    }

    private function getCheckoutToOrderRateStat(): Stat
    {
        $rate = $this->calculateCheckoutToOrderRate();
        $formattedRate = round($rate, 1) . '%';

        $stat = Stat::make('Checkout → Order', $formattedRate)
            ->description('Users who completed purchase')
            ->descriptionIcon('heroicon-m-credit-card');

        if ($rate < 40) {
            $stat->color('danger');
        } elseif ($rate < 70) {
            $stat->color('warning');
        } else {
            $stat->color('success');
        }

        return $stat;
    }

    private function getAbandonedCartRateStat(): Stat
    {
        $rate = $this->calculateAbandonedCartRate();
        $formattedRate = round($rate, 1) . '%';

        $stat = Stat::make('Cart Abandonment', $formattedRate)
            ->description('Carts not converted in 24h')
            ->descriptionIcon('heroicon-m-x-circle');

        if ($rate > 80) {
            $stat->color('danger');
        } elseif ($rate > 60) {
            $stat->color('warning');
        } else {
            $stat->color('success');
        }

        return $stat;
    }

    private function calculateConversionRate(string $period = 'today'): float
    {
        $now = Carbon::now();

        if ($period === 'yesterday') {
            $start = $now->copy()->subDay()->startOfDay();
            $end = $now->copy()->subDay()->endOfDay();
        } else {
            $start = $now->copy()->startOfDay();
            $end = $now->copy()->endOfDay();
        }

        $visitors = Sessions::query()
            ->whereBetween('created_at', [$start, $end])
            ->distinct('session_id')
            ->count('session_id');

        $orders = Order::query()
            ->where('order_completed', 1)
            ->whereBetween('created_at', [$start, $end])
            ->count();

        if ($visitors === 0) {
            return 0.0;
        }

        return ($orders / $visitors) * 100;
    }

    private function calculateCartToCheckoutRate(): float
    {
        $today = Carbon::now()->startOfDay();

        $activeCarts = Cart::query()
            ->where('updated_at', '>=', $today->copy()->subHours(24))
            ->whereNotNull('order_id')
            ->count();

        $totalCarts = Cart::query()
            ->where('updated_at', '>=', $today->copy()->subHours(24))
            ->count();

        if ($totalCarts === 0) {
            return 0.0;
        }

        return ($activeCarts / $totalCarts) * 100;
    }

    private function calculateCheckoutToOrderRate(): float
    {
        $today = Carbon::now()->startOfDay();

        $completedOrders = Order::query()
            ->where('order_completed', 1)
            ->whereBetween('created_at', [$today, Carbon::now()])
            ->count();

        $totalOrders = Order::query()
            ->whereBetween('created_at', [$today, Carbon::now()])
            ->count();

        if ($totalOrders === 0) {
            return 0.0;
        }

        return ($completedOrders / $totalOrders) * 100;
    }

    private function calculateAbandonedCartRate(): float
    {
        $threshold = Carbon::now()->subHours(24);

        $abandonedCarts = Cart::query()
            ->whereNull('order_id')
            ->where('updated_at', '<=', $threshold)
            ->where('updated_at', '>=', $threshold->copy()->subHours(24))
            ->count();

        $totalActiveCarts = Cart::query()
            ->where('updated_at', '>=', $threshold)
            ->count();

        if ($totalActiveCarts === 0) {
            return 0.0;
        }

        return ($abandonedCarts / $totalActiveCarts) * 100;
    }

    private function formatChangeDescription(float $change, string $period): string
    {
        $direction = $change >= 0 ? 'up' : 'down';
        $absChange = abs($change);

        return sprintf('%s %.2f%% %s', $direction, $absChange, $period);
    }

    private function getConversionColor(float $rate): string
    {
        if ($rate >= 5) {
            return 'success';
        } elseif ($rate >= 2) {
            return 'primary';
        } elseif ($rate >= 1) {
            return 'warning';
        } else {
            return 'danger';
        }
    }
}
