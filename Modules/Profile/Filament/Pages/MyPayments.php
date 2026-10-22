<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Modules\Customer\Models\Customer;
use Modules\Order\Models\Order;
use Modules\Payment\Models\Payment;
use Modules\Profile\Filament\Concerns\InteractsWithCurrentCustomer;

/**
 * The signed-in buyer's payments — payment rows attached to their orders
 * (rel_type = Order, rel_id in the user's order ids). Only shown in the
 * sidebar when the user actually has payments.
 */
class MyPayments extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithCurrentCustomer;

    protected string $view = 'modules.profile::pages.my-payments';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-credit-card';
    protected static ?int $navigationSort = 3;

    public function getTitle(): string
    {
        return __('Payments');
    }

    public static function getNavigationLabel(): string
    {
        return __('Payments');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Orders & Billing');
    }

    public function table(Table $table): Table
    {
        $orderIds = $this->currentCustomerOrderIds() ?: [0];

        return $table
            ->query(
                Payment::query()
                    ->where('rel_type', \Modules\Order\Models\Order::class)
                    ->whereIn('rel_id', $orderIds)
            )
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('Date'))
                    ->dateTime('M j, Y')
                    ->sortable(),
                TextColumn::make('transaction_id')
                    ->label(__('Transaction'))
                    ->searchable()
                    ->limit(24),
                TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->formatStateUsing(fn ($state, Payment $r) => currency_format($state) . ' ' . strtoupper((string) $r->currency)),
                TextColumn::make('payment_provider')
                    ->label(__('Method')),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(__('No payments yet'))
            ->emptyStateIcon('heroicon-o-credit-card');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::customerHasPayments();
    }

    protected static function customerHasPayments(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }
        $customerId = Customer::where('user_id', $user->id)->value('id')
            ?: Customer::where('email', $user->email)->value('id');
        if (! $customerId) {
            return false;
        }

        $orderIds = Order::where('customer_id', $customerId)->pluck('id')->all();
        if (empty($orderIds)) {
            return false;
        }

        return Payment::where('rel_type', \Modules\Order\Models\Order::class)
            ->whereIn('rel_id', $orderIds)
            ->exists();
    }
}
