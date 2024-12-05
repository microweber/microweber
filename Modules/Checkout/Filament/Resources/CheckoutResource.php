<?php

namespace Modules\Checkout\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Radio;
use Modules\Checkout\Filament\Actions\PaymentAction;
use Illuminate\Support\Facades\Event;
use Modules\Checkout\Livewire\ReviewOrder;
use Modules\Checkout\Livewire\CartItems;

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
                Section::make('Personal Information')
                    ->schema([
                        TextInput::make('first_name')
                            ->required()
                            ->maxLength(255)
                            ->afterStateUpdated(function ($state, callable $get, $livewire) {
                                app()->user_manager->session_set('checkout_first_name', $state);
                                $livewire->dispatch('cart-updated');
                            })
                            ->default(fn() => app()->user_manager->session_get('checkout_first_name')),

                        TextInput::make('last_name')
                            ->required()
                            ->maxLength(255)
                            ->afterStateUpdated(function ($state, callable $get, $livewire) {
                                app()->user_manager->session_set('checkout_last_name', $state);
                                $livewire->dispatch('cart-updated');
                            })
                            ->default(fn() => app()->user_manager->session_get('checkout_last_name')),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->afterStateUpdated(function ($state, callable $get, $livewire) {
                                app()->user_manager->session_set('checkout_email', $state);
                                $livewire->dispatch('cart-updated');
                            })
                            ->default(fn() => app()->user_manager->session_get('checkout_email')),

                        TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->afterStateUpdated(function ($state, callable $get, $livewire) {
                                app()->user_manager->session_set('checkout_phone', $state);
                                $livewire->dispatch('cart-updated');
                            })
                            ->default(fn() => app()->user_manager->session_get('checkout_phone')),
                    ])
                    ->columns(2),

                Section::make('Shipping Address')
                    ->schema([
                        Select::make('country')
                            ->required()
                            ->searchable()
                            ->afterStateUpdated(function ($state, callable $get, $livewire) {
                                app()->user_manager->session_set('checkout_country', $state);
                                $livewire->dispatch('cart-updated');
                            })
                            ->options(function () {
                                return app()->country_manager->get_countries();
                            })
                            ->default(fn() => app()->user_manager->session_get('checkout_country')),

                        TextInput::make('city')
                            ->required()
                            ->maxLength(255)
                            ->afterStateUpdated(function ($state, callable $get, $livewire) {
                                app()->user_manager->session_set('checkout_city', $state);
                                $livewire->dispatch('cart-updated');
                            })
                            ->default(fn() => app()->user_manager->session_get('checkout_city')),

                        TextInput::make('state')
                            ->required()
                            ->maxLength(255)
                            ->afterStateUpdated(function ($state, callable $get, $livewire) {
                                app()->user_manager->session_set('checkout_state', $state);
                                $livewire->dispatch('cart-updated');
                            })
                            ->default(fn() => app()->user_manager->session_get('checkout_state')),

                        TextInput::make('postal_code')
                            ->required()
                            ->maxLength(20)
                            ->afterStateUpdated(function ($state, callable $get, $livewire) {
                                app()->user_manager->session_set('checkout_postal_code', $state);
                                $livewire->dispatch('cart-updated');
                            })
                            ->default(fn() => app()->user_manager->session_get('checkout_postal_code')),

                        TextInput::make('address')
                            ->required()
                            ->maxLength(255)
                            ->afterStateUpdated(function ($state, callable $get, $livewire) {
                                app()->user_manager->session_set('checkout_address', $state);
                                $livewire->dispatch('cart-updated');
                            })
                            ->default(fn() => app()->user_manager->session_get('checkout_address')),
                    ])
                    ->columns(2),

                Section::make('Order Items')
                    ->schema([
                        Forms\Components\Livewire::make(CartItems::class)
                            ->columnSpanFull(),
                    ]),

//                Section::make('Order Summary')
//                    ->schema([
//                        Forms\Components\Livewire::make(ReviewOrder::class)
//                            ->columnSpanFull(),
//                    ]),

                Section::make('Shipping Method')
                    ->schema([
                        Radio::make('shipping_method')
                            ->options(function () {
                                $methods = app()->shipping_method_manager->getProviders();
                                $options = [];
                                foreach ($methods as $id) {
                                    $options[$id] = ucfirst($id);
                                }
                                return $options;
                            })
                            ->afterStateUpdated(function ($state, callable $get, $livewire) {
                                app()->user_manager->session_set('shipping_provider', $state);
                                $livewire->dispatch('shipping-method-changed');
                            })
                            ->default(fn() => app()->user_manager->session_get('shipping_provider'))
                            ->live()
                            ->columnSpanFull(),

                        Forms\Components\View::make('modules.shipping::filament.components.shipping-method-renderer')
                            ->statePath('shipping_method')
                            ->live()
                            ->columnSpanFull(),
                    ]),


                Section::make('Payment Method')
                    ->schema(function (callable $get) {
                        $methods = app()->payment_method_manager->getProviders();

                        $options = [];
                        foreach ($methods as $provider) {
                            $options[$provider['provider']] = $provider['name'] ?? ucfirst($provider['provider']);
                        }

                        $selected = app()->user_manager->session_get('payment_provider');

                        $providerForm = app()->payment_method_manager->getForm($selected);

                        $formSchema = [
                            Radio::make('payment_method')
                                ->options($options)
                                ->afterStateUpdated(function ($state, callable $get, $livewire) {
                                    app()->user_manager->session_set('payment_provider', $state);
                                })
                                ->default(fn() => app()->user_manager->session_get('payment_provider'))
                                ->live()
                                ->reactive()
                                ->columnSpanFull(),
                        ];
                        if ($providerForm) {
                            $formSchema = array_merge($formSchema, $providerForm);
                        }


                        return $formSchema;


                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\CheckoutPage::route('/'),
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
