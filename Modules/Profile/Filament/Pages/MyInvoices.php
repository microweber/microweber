<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Modules\Customer\Models\Customer;
use Modules\Invoice\Models\Invoice;
use Modules\Profile\Filament\Concerns\InteractsWithCurrentCustomer;

/**
 * The signed-in buyer's invoices. Only shown in the sidebar when the user
 * actually has invoices (shouldRegisterNavigation checks existence).
 */
class MyInvoices extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithCurrentCustomer;

    protected string $view = 'modules.profile::pages.my-invoices';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 2;

    public function getTitle(): string
    {
        return __('Invoices');
    }

    public static function getNavigationLabel(): string
    {
        return __('Invoices');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Orders & Billing');
    }

    public function table(Table $table): Table
    {
        $customerId = $this->currentCustomerId() ?: 0;

        return $table
            ->query(Invoice::query()->where('customer_id', $customerId))
            ->columns([
                TextColumn::make('invoice_number')
                    ->label(__('Invoice #'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('invoice_date')
                    ->label(__('Date'))
                    ->date('M j, Y')
                    ->sortable(),
                TextColumn::make('total')
                    ->label(__('Total'))
                    ->formatStateUsing(fn ($state) => currency_format($state)),
                TextColumn::make('due_amount')
                    ->label(__('Due'))
                    ->formatStateUsing(fn ($state) => currency_format($state)),
                TextColumn::make('paid_status')
                    ->label(__('Status'))
                    ->badge(),
            ])
            ->defaultSort('invoice_date', 'desc')
            ->emptyStateHeading(__('No invoices yet'))
            ->emptyStateIcon('heroicon-o-document-text');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::customerHasInvoices();
    }

    protected static function customerHasInvoices(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }
        $customerId = Customer::where('user_id', $user->id)->value('id')
            ?: Customer::where('email', $user->email)->value('id');

        return $customerId ? Invoice::where('customer_id', $customerId)->exists() : false;
    }
}
