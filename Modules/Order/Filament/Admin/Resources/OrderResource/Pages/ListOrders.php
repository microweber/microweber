<?php

namespace Modules\Order\Filament\Admin\Resources\OrderResource\Pages;

use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Resources\Pages\ListRecords;
use Modules\Coupons\Filament\Resources\CouponResource;
use Modules\Offer\Filament\Admin\Resources\OfferResource;
use Modules\Order\Filament\Admin\Resources\OrderResource;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource;
use Modules\Payment\Filament\Admin\Resources\PaymentResource;
use Modules\Settings\Filament\Pages\AdminShopGeneralPage;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource;


class ListOrders extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // AI-783 (task-2026-05-17-378d85) — unify the header CTA label with
            // the empty-state body CTA ("+ Add order"). Previously "Create Order"
            // (header) vs "+ Add order" (empty state body) — same affordance,
            // two different labels + visual styles. Now both read "+ Add order"
            // with primary color, matching the AI-736 Pages-list pattern.
            Actions\CreateAction::make()
                ->label('+ Add order')
                ->icon('heroicon-o-plus')
                ->color('primary'),

            Actions\ActionGroup::make([

Actions\Action::make('payments_list')
                ->label('Payment transactions')
                ->url(PaymentResource::getUrl('index'))
                ->icon('heroicon-o-banknotes'),

Actions\Action::make('payment_provider_settings')
                ->label('Payment Settings')
                ->url(PaymentProviderResource::getUrl('index'))
                ->icon('heroicon-o-credit-card'),


Actions\Action::make('shipping_provider_settings')
                ->label('Shipping Settings')
                ->url(ShippingProviderResource::getUrl('index'))
                ->icon('heroicon-o-truck'),


Actions\Action::make('shop_general_settings')
                ->label('Shop Settings')
                ->url(AdminShopGeneralPage::getUrl())
                ->icon('heroicon-o-cog-6-tooth'),


Actions\Action::make('coupons')
                ->label('Coupons')
                ->url(CouponResource::getUrl('index'))
                ->icon('heroicon-o-ticket'),


Actions\Action::make('discount_prices')
                ->label('Discount Prices')
                ->url(OfferResource::getUrl('index'))
                ->icon('heroicon-o-tag'),


            ])->icon('heroicon-o-cog-6-tooth')->tooltip('Settings'),
        ];
    }

    public function getHeaderWidgets(): array
    {
        return OrderResource::getWidgets();
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 4;
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'new' => Tab::make()->query(fn($query) => $query->where('order_status', 'new')),
            'processing' => Tab::make()->query(fn($query) => $query->where('order_status', 'processing')),
            'shipped' => Tab::make()->query(fn($query) => $query->where('order_status', 'shipped')),
            'delivered' => Tab::make()->query(fn($query) => $query->where('order_status', 'delivered')),
            'cancelled' => Tab::make()->query(fn($query) => $query->where('order_status', 'cancelled')),
            'refunded' => Tab::make()->query(fn($query) => $query->where('order_status', 'refunded')),
        ];
    }
}
