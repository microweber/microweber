<?php
namespace Modules\Order\Filament\Admin\Resources\OrderResource\Widgets;


use Filament\Support\Concerns\CanBeLazy;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Modules\Order\Filament\Admin\Resources\OrderResource\Pages\ListOrders;
use Modules\Order\Models\Order;

class OrderStats extends BaseWidget
{
    use CanBeLazy;
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListOrders::class;
    }

    protected function getStats(): array
    {
        $orderData = Trend::model(Order::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            Stat::make('Orders', $this->getPageTableQuery()->count())
                ->chart(
                    $orderData
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
            Stat::make('New orders', $this->getPageTableQuery()->whereIn('order_status', ['new'])->count()),
            Stat::make('Open orders', $this->getPageTableQuery()->whereIn('order_status', ['open', 'processing'])->count()),
            Stat::make('Average price', $this->formatAveragePriceByCurrency()),
        ];
    }

    /**
     * TASK-021 / TICKET-J / AI-39 (cycle-61 2026-05-08): cross-currency
     * arithmetic on `amount` is meaningless when a store accepts more
     * than one currency (USD 100 + EUR 100 != 200 of anything). Group
     * the avg() by currency and render a per-currency line so the
     * stats pill stays informative across the multi-currency case.
     *
     * Single-currency stores keep the prior shape (one number, two
     * decimals, no currency tag) so existing fixtures and tests are
     * unaffected.
     */
    protected function formatAveragePriceByCurrency(): string
    {
        $rows = $this->getPageTableQuery()
            ->selectRaw('currency, AVG(amount) as avg_amount')
            ->whereNotNull('amount')
            ->groupBy('currency')
            ->orderBy('currency')
            ->get();

        if ($rows->isEmpty()) {
            return '0.00';
        }

        $defaultSymbol = function_exists('currency_symbol')
            ? (currency_symbol() ?: '$')
            : '$';

        if ($rows->count() === 1) {
            $symbol = self::currencyCodeToSymbol($rows->first()->currency) ?? $defaultSymbol;
            return $symbol . ' ' . number_format((float) $rows->first()->avg_amount, 2);
        }

        return $rows
            ->map(function ($row) use ($defaultSymbol) {
                $symbol = self::currencyCodeToSymbol($row->currency) ?? $defaultSymbol;
                return $symbol . ' ' . number_format((float) $row->avg_amount, 2);
            })
            ->implode(' · ');
    }

    protected static function currencyCodeToSymbol(?string $code): ?string
    {
        if (!$code) return null;
        return match (strtoupper($code)) {
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'CAD' => 'C$',
            'AUD' => 'A$',
            'JPY' => '¥',
            default => strtoupper($code),
        };
    }
}
