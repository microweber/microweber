<?php

namespace Modules\Checkout\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Event;
use Modules\Checkout\Livewire\ReviewOrder;
use Modules\Checkout\Livewire\CartItems;
use Modules\Checkout\Filament\Resources\Pages;

class CheckoutResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Checkout';
    protected static ?string $modelLabel = 'Checkout';
    protected static ?string $slug = 'checkout';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Grid::make()
                            ->schema([
                                Section::make('Personal Information')
                                    ->schema([
                                        TextInput::make('first_name')
                                            ->required()
                                            ->maxLength(255)
                                            ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                checkout_set_user_info('first_name', $state);
                                                $livewire->dispatch('cart-updated');
                                            })
                                            ->default(fn() => checkout_get_user_info('first_name')),

                                        TextInput::make('last_name')
                                            ->required()
                                            ->maxLength(255)
                                            ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                checkout_set_user_info('last_name', $state);
                                                $livewire->dispatch('cart-updated');
                                            })
                                            ->default(fn() => checkout_get_user_info('last_name')),

                                        TextInput::make('email')
                                            ->email()
                                            ->required()
                                            ->maxLength(255)
                                            ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                checkout_set_user_info('email', $state);
                                                $livewire->dispatch('cart-updated');
                                            })
                                            ->default(fn() => checkout_get_user_info('email')),

                                        TextInput::make('phone')
                                            ->tel()
                                            ->required()
                                            ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                checkout_set_user_info('phone', $state);
                                                $livewire->dispatch('cart-updated');
                                            })
                                            ->default(fn() => checkout_get_user_info('phone')),
                                    ])
                                    ->columns(2),

                                Section::make('Shipping Address')
                                    ->schema([
                                        Select::make('country')
                                            ->required()
                                            ->searchable()
                                            ->native()
                                            ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                checkout_set_user_info('country', $state);
                                                $livewire->dispatch('cart-updated');
                                            })
                                            ->options(function () {
                                                return app()->country_manager->getCountries();
                                            })
                                            ->default(fn() => checkout_get_user_info('country')),

                                        TextInput::make('city')
                                            ->required()
                                            ->maxLength(255)
                                            ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                checkout_set_user_info('city', $state);
                                                $livewire->dispatch('cart-updated');
                                            })
                                            ->default(fn() => checkout_get_user_info('city')),

                                        TextInput::make('state')
                                            ->required()
                                            ->maxLength(255)
                                            ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                checkout_set_user_info('state', $state);
                                                $livewire->dispatch('cart-updated');
                                            })
                                            ->default(fn() => checkout_get_user_info('state')),

                                        TextInput::make('postal_code')
                                            ->required()
                                            ->maxLength(20)
                                            ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                checkout_set_user_info('postal_code', $state);
                                                $livewire->dispatch('cart-updated');
                                            })
                                            ->default(fn() => checkout_get_user_info('postal_code')),

                                        Forms\Components\Textarea::make('address')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull()
                                            ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                checkout_set_user_info('address', $state);
                                                $livewire->dispatch('cart-updated');
                                            })
                                            ->default(fn() => checkout_get_user_info('address')),
                                    ])
                                    ->columns(2),


                                Section::make('Shipping Method')
                                    ->schema(function (Forms\Get $get) {
                                        $methods = app()->shipping_method_manager->getProviders();
                                        $options = [];
                                        foreach ($methods as $provider) {
                                            $options[$provider['id']] = $provider['name'] ?? ucfirst($provider['provider']);
                                        }

                                        $selectedId = checkout_get_user_info('shipping_provider_id');
                                        $hasDriver = app()->shipping_method_manager->getProviderById($selectedId);
                                        $providerForm = [];
                                        if ($hasDriver) {
                                            $providerForm = app()->shipping_method_manager->getForm($selectedId);
                                        }

                                        $formSchema = [
                                            Radio::make('shipping_provider_id')
                                                ->label('Shipping Method')
                                                ->options($options)
                                                ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                    checkout_set_user_info('shipping_provider_id', $state);
                                                    $livewire->dispatch('reload-cart');
                                                })
                                                ->default(fn() => checkout_get_user_info('shipping_provider_id'))
                                                ->live()
                                                ->reactive()
                                                ->columnSpanFull(),
                                        ];
                                        if ($providerForm) {
                                            $formSchema = array_merge($formSchema, $providerForm);
                                        }

                                        return $formSchema;
                                    }),

                            ])
                            ->columnSpan(1),

                        Grid::make()
                            ->schema([
                                Section::make('Order Items')
                                    ->schema([
                                        Forms\Components\Livewire::make(CartItems::class)
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Payment Method')
                                    ->schema(function (Forms\Get $get) {
                                        $methods = app()->payment_method_manager->getProviders();

                                        $options = [];
                                        foreach ($methods as $provider) {
                                            $options[$provider['id']] = $provider['name'] ?? ucfirst($provider['provider']);
                                        }

                                        $selectedId = checkout_get_user_info('payment_provider_id');
                                        $hasDriver = app()->payment_method_manager->getProviderById($selectedId);
                                        $providerForm = [];
                                        if ($hasDriver) {
                                            $providerForm = app()->payment_method_manager->getForm($selectedId);
                                        }
                                        $formSchema = [
                                            Radio::make('payment_method_id')
                                                ->label('Payment Method')
                                                ->options($options)
                                                ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                    checkout_set_user_info('payment_provider_id', $state);
                                                })
                                                ->default(fn() => checkout_get_user_info('payment_provider_id'))
                                                ->live()
                                                ->reactive()
                                                ->columnSpanFull(),
                                        ];
                                        if ($providerForm) {
                                            $formSchema = array_merge($formSchema, $providerForm);
                                        }

                                        return $formSchema;
                                    }),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\CheckoutPage::route('/'),
            'success' => Pages\CheckoutSuccessPage::route('/success'),
            'failed' => Pages\CheckoutFailedPage::route('/failed'),
            'cancelled' => Pages\CheckoutCancelledPage::route('/cancelled'),
        ];
    }

    public static function getBreadcrumb(): string
    {
        return '';
    }

    public static function getActions(): array
    {
        return [

        ];
    }
}
