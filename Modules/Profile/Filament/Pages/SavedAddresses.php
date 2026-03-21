<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Address\Models\Address;
use Modules\Customer\Models\Customer;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;

class SavedAddresses extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'modules.profile::pages.saved-addresses';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-map-pin';
    protected static ?int $navigationSort = 3;

    public function getTitle(): string
    {
        return __('Saved Addresses');
    }

    public static function getNavigationLabel(): string
    {
        return __('Saved Addresses');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Profile');
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
                Tables\Actions\CreateAction::make()
                    ->label(__('Add New Address'))
                    ->modalHeading(__('Add New Address'))
                    ->modalWidth('2xl')
                    ->form([
                        TextInput::make('name')
                            ->label(__('Address Label (e.g., Home, Office)'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('e.g., Home, Office, Parents House')),

                        Select::make('type')
                            ->label(__('Address Type'))
                            ->required()
                            ->options([
                                Address::SHIPPING_TYPE => __('Shipping'),
                                Address::BILLING_TYPE => __('Billing'),
                                Address::OTHER_TYPE => __('Other'),
                            ])
                            ->default(Address::SHIPPING_TYPE),

                        TextInput::make('address_street_1')
                            ->label(__('Street Address'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('123 Main Street')),

                        TextInput::make('address_street_2')
                            ->label(__('Apartment, Suite, etc. (Optional)'))
                            ->maxLength(255)
                            ->placeholder(__('Apt 4B, Suite 100')),

                        TextInput::make('city')
                            ->label(__('City'))
                            ->required()
                            ->maxLength(255),

                        TextInput::make('state')
                            ->label(__('State / Province'))
                            ->maxLength(255),

                        TextInput::make('zip')
                            ->label(__('Postal Code / ZIP'))
                            ->required()
                            ->maxLength(20),

                        Select::make('country_id')
                            ->label(__('Country'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('country', 'name')
                            ->default(function () {
                                return \Modules\Country\Models\Country::where('name', 'United States')->value('id');
                            }),

                        TextInput::make('phone')
                            ->label(__('Phone Number (Optional)'))
                            ->tel()
                            ->maxLength(50),
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        $customer = $this->getCustomer();
                        if ($customer) {
                            $data['rel_type'] = 'customer';
                            $data['rel_id'] = $customer->id;
                        }
                        return $data;
                    })
                    ->after(function () {
                        Notification::make()
                            ->title(__('Address Added'))
                            ->body(__('Your address has been saved successfully.'))
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->label(__('Edit'))
                    ->modalHeading(__('Edit Address'))
                    ->modalWidth('2xl')
                    ->form([
                        TextInput::make('name')
                            ->label(__('Address Label'))
                            ->required()
                            ->maxLength(255),

                        Select::make('type')
                            ->label(__('Address Type'))
                            ->required()
                            ->options([
                                Address::SHIPPING_TYPE => __('Shipping'),
                                Address::BILLING_TYPE => __('Billing'),
                                Address::OTHER_TYPE => __('Other'),
                            ]),

                        TextInput::make('address_street_1')
                            ->label(__('Street Address'))
                            ->required()
                            ->maxLength(255),

                        TextInput::make('address_street_2')
                            ->label(__('Apartment, Suite, etc. (Optional)'))
                            ->maxLength(255),

                        TextInput::make('city')
                            ->label(__('City'))
                            ->required()
                            ->maxLength(255),

                        TextInput::make('state')
                            ->label(__('State / Province'))
                            ->maxLength(255),

                        TextInput::make('zip')
                            ->label(__('Postal Code / ZIP'))
                            ->required()
                            ->maxLength(20),

                        Select::make('country_id')
                            ->label(__('Country'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('country', 'name'),

                        TextInput::make('phone')
                            ->label(__('Phone Number (Optional)'))
                            ->tel()
                            ->maxLength(50),
                    ])
                    ->after(function () {
                        Notification::make()
                            ->title(__('Address Updated'))
                            ->body(__('Your address has been updated successfully.'))
                            ->success()
                            ->send();
                    }),

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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
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
                Tables\Actions\CreateAction::make()
                    ->label(__('Add Your First Address'))
                    ->modalHeading(__('Add New Address'))
                    ->modalWidth('2xl')
                    ->form([
                        TextInput::make('name')
                            ->label(__('Address Label (e.g., Home, Office)'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('e.g., Home, Office, Parents House')),

                        Select::make('type')
                            ->label(__('Address Type'))
                            ->required()
                            ->options([
                                Address::SHIPPING_TYPE => __('Shipping'),
                                Address::BILLING_TYPE => __('Billing'),
                                Address::OTHER_TYPE => __('Other'),
                            ])
                            ->default(Address::SHIPPING_TYPE),

                        TextInput::make('address_street_1')
                            ->label(__('Street Address'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('123 Main Street')),

                        TextInput::make('address_street_2')
                            ->label(__('Apartment, Suite, etc. (Optional)'))
                            ->maxLength(255)
                            ->placeholder(__('Apt 4B, Suite 100')),

                        TextInput::make('city')
                            ->label(__('City'))
                            ->required()
                            ->maxLength(255),

                        TextInput::make('state')
                            ->label(__('State / Province'))
                            ->maxLength(255),

                        TextInput::make('zip')
                            ->label(__('Postal Code / ZIP'))
                            ->required()
                            ->maxLength(20),

                        Select::make('country_id')
                            ->label(__('Country'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('country', 'name')
                            ->default(function () {
                                return \Modules\Country\Models\Country::where('name', 'United States')->value('id');
                            }),

                        TextInput::make('phone')
                            ->label(__('Phone Number (Optional)'))
                            ->tel()
                            ->maxLength(50),
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        $customer = $this->getCustomer();
                        if ($customer) {
                            $data['rel_type'] = 'customer';
                            $data['rel_id'] = $customer->id;
                        }
                        return $data;
                    }),
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
