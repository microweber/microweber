<?php
namespace Modules\Order\Filament\Admin\Resources\OrderResource\Widgets;


use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Modules\Order\Filament\Admin\Resources\OrderResource\Pages\ListOrders;
use Modules\Order\Models\Order;

class OrderStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

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
            Stat::make('Average price', number_format($this->getPageTableQuery()->avg('amount'), 2)),
        ];
    }
}
