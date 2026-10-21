<?php

namespace Modules\Vendors\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use Modules\Vendors\Services\VendorManager;

/**
 * Vendor self-service: the orders that contain this vendor's products.
 *
 * Scoped to the signed-in vendor via the order→cart→product(created_by)
 * mapping — the same ownership rule used everywhere in the module. Each row
 * shows only this vendor's slice of the order (their line items + subtotal),
 * never other vendors' items or the whole-order total.
 */
class MyVendorOrders extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected string $view = 'modules.vendors::filament.pages.my-vendor-orders';
    protected static ?int $navigationSort = 12;

    public function getTitle(): string
    {
        return __('My Orders');
    }

    public static function getNavigationLabel(): string
    {
        return __('My Orders');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Vendor');
    }

    public function table(Table $table): Table
    {
        $productIds = $this->vendorProductIds();

        return $table
            ->heading(__('My Orders'))
            ->query(
                Order::query()->whereIn('id', function ($q) use ($productIds) {
                    $q->select('order_id')
                        ->from('cart')
                        ->whereIn('rel_id', $productIds ?: [0])
                        ->where('order_completed', 1);
                })
            )
            ->columns([
                TextColumn::make('id')
                    ->label(__('Order'))
                    ->formatStateUsing(fn ($state, Order $r) => '#' . ($r->order_reference_id ?: $r->id))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Date'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('buyer')
                    ->label(__('Buyer'))
                    ->state(fn (Order $r) => trim(($r->first_name ?? '') . ' ' . ($r->last_name ?? '')) ?: ($r->email ?? '—')),
                TextColumn::make('my_items')
                    ->label(__('Your items'))
                    ->state(fn (Order $r) => $this->vendorItemCount($r->id)),
                TextColumn::make('my_subtotal')
                    ->label(__('Your subtotal'))
                    ->state(fn (Order $r) => currency_format($this->vendorSubtotal($r->id))),
                IconColumn::make('is_paid')
                    ->label(__('Paid'))
                    ->boolean(),
            ])
            ->recordActions([
                Action::make('items')
                    ->label(__('Your items'))
                    ->icon('heroicon-o-list-bullet')
                    ->modalHeading(__('Your items in this order'))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel(__('Close'))
                    ->modalContent(fn (Order $r) => view('modules.vendors::filament.pages.partials.order-items', [
                        'items' => $this->vendorItems($r->id),
                    ])),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(__('No orders yet'))
            ->emptyStateDescription(__('Orders containing your products will appear here.'))
            ->emptyStateIcon('heroicon-o-inbox');
    }

    /** @return array<int,int> this vendor's product ids */
    protected function vendorProductIds(): array
    {
        return Product::query()
            ->filter(['author' => (int) Auth::id()])
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    protected function vendorItemsQuery(int $orderId)
    {
        return DB::table('cart')
            ->where('order_id', $orderId)
            ->where('order_completed', 1)
            ->whereIn('rel_id', $this->vendorProductIds() ?: [0]);
    }

    protected function vendorItemCount(int $orderId): int
    {
        return (int) $this->vendorItemsQuery($orderId)->sum('qty');
    }

    protected function vendorSubtotal(int $orderId): float
    {
        return (float) $this->vendorItemsQuery($orderId)
            ->select(DB::raw('COALESCE(SUM(price * qty), 0) as t'))
            ->value('t');
    }

    protected function vendorItems(int $orderId): array
    {
        return $this->vendorItemsQuery($orderId)
            ->get(['title', 'qty', 'price'])
            ->map(fn ($i) => [
                'title' => $i->title,
                'qty' => $i->qty,
                'price' => $i->price,
            ])
            ->all();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check();
    }
}
