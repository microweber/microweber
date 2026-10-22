<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Address\Models\Address;
use Modules\Customer\Models\Customer;
use Filament\Notifications\Notification;

class SavedAddresses extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'modules.profile::pages.saved-addresses';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-map-pin';
    protected static ?int $navigationSort = 6;

    public function getTitle(): string
    {
        return __('Addresses');
    }

    public static function getNavigationLabel(): string
    {
        return __('Addresses');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Orders & Billing');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getAddressesQuery())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Address Label'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            Address::BILLING_TYPE => __('Billing'),
                            Address::SHIPPING_TYPE => __('Shipping'),
                            default => __('Other'),
                        };
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            Address::BILLING_TYPE => 'primary',
                            Address::SHIPPING_TYPE => 'success',
                            default => 'gray',
                        };
                    }),

                Tables\Columns\TextColumn::make('address_street_1')
                    ->label(__('Street Address'))
                    ->wrap()
                    ->formatStateUsing(function ($record) {
                        $parts = [$record->address_street_1];
                        if ($record->address_street_2) {
                            $parts[] = $record->address_street_2;
                        }
                        return implode(', ', array_filter($parts));
                    }),

                Tables\Columns\TextColumn::make('city')
                    ->label(__('City / State'))
                    ->formatStateUsing(function ($record) {
                        $parts = [$record->city, $record->state];
                        return implode(', ', array_filter($parts));
                    }),

                Tables\Columns\TextColumn::make('zip')
                    ->label(__('Postal Code'))
                    ->toggleable(),

                Tables\Columns\TextColumn::make('country.name')
                    ->label(__('Country'))
                    ->default($record->country ?? '-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('Address Type'))
                    ->options([
                        Address::BILLING_TYPE => __('Billing'),
                        Address::SHIPPING_TYPE => __('Shipping'),
                        Address::OTHER_TYPE => __('Other'),
                    ]),
            ])
            ->headerActions([
                Action::make('create')
                    ->label(__('Add New Address'))
                    ->icon('heroicon-o-plus')
                    ->url(CreateAddress::getUrl()),
            ])
            ->actions([
                Action::make('edit')
                    ->label(__('Edit'))
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (Address $record): string => EditAddress::getUrl() . '?address=' . $record->id),

                DeleteAction::make()
                    ->label(__('Delete'))
                    ->requiresConfirmation()
                    ->modalHeading(__('Delete Address'))
                    ->modalDescription(__('Are you sure you want to delete this address? This action cannot be undone.'))
                    ->modalSubmitActionLabel(__('Yes, Delete'))
                    ->after(function () {
                        Notification::make()
                            ->title(__('Address Deleted'))
                            ->body(__('The address has been deleted.'))
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make()
                        ->label(__('Delete Selected'))
                        ->requiresConfirmation()
                        ->modalHeading(__('Delete Selected Addresses'))
                        ->modalDescription(__('Are you sure you want to delete the selected addresses?')),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(__('No Saved Addresses'))
            ->emptyStateDescription(__('You have not saved any addresses yet. Add your first address to speed up checkout.'))
            ->emptyStateIcon('heroicon-o-map-pin')
            ->emptyStateActions([
                Action::make('create')
                    ->label(__('Add Your First Address'))
                    ->icon('heroicon-o-plus')
                    ->url(CreateAddress::getUrl()),
            ]);
    }

    protected function getAddressesQuery(): Builder
    {
        $customer = $this->getCustomer();

        if (!$customer) {
            return Address::query()->whereRaw('1 = 0'); // Return empty query
        }

        return Address::query()
            ->where('rel_type', 'customer')
            ->where('rel_id', $customer->id)
            ->with('country');
    }

    protected function getCustomer(): ?Customer
    {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        // Check if user has a customer record
        $customer = Customer::where('user_id', $user->id)->first();

        if ($customer) {
            return $customer;
        }

        // Fallback: try to find by email
        return Customer::where('email', $user->email)->first();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check();
    }
}
