<?php

namespace Modules\Checkout\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Modules\Checkout\Filament\Actions\PaymentAction;
use Illuminate\Support\Facades\Event;
use Modules\Checkout\Livewire\ReviewOrder;
use Modules\Checkout\Livewire\CartItems;
use Modules\Payment\Livewire\PaymentMethodSelector;

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
                            ->lazy()
                            ->columnSpanFull(),
                    ]),

                Section::make('Order Summary')
                    ->schema([
                        Forms\Components\Livewire::make(ReviewOrder::class)
                            ->lazy()
                            ->columnSpanFull(),
                    ]),

                Section::make('Payment Method')
                    ->schema([
                        Forms\Components\Livewire::make(PaymentMethodSelector::class)
                            ->visible(fn() => app()->payment_method_manager->hasProviders())
                            ->columnSpanFull(),
                    ]),
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
            PaymentAction::make('process_payment')
                ->label('Process Payment')
                ->onRequest(function (array $data) {
                    try {
                        Event::dispatch('checkout.payment.before');

                        // Create order
                        $order = app()->order_manager->create([
                            'first_name' => app()->user_manager->session_get('checkout_first_name'),
                            'last_name' => app()->user_manager->session_get('checkout_last_name'),
                            'email' => app()->user_manager->session_get('checkout_email'),
                            'phone' => app()->user_manager->session_get('checkout_phone'),
                            'address' => app()->user_manager->session_get('checkout_address'),
                            'city' => app()->user_manager->session_get('checkout_city'),
                            'state' => app()->user_manager->session_get('checkout_state'),
                            'postal_code' => app()->user_manager->session_get('checkout_postal_code'),
                            'country' => app()->user_manager->session_get('checkout_country'),
                        ]);

                        Event::dispatch('checkout.payment.after', ['order' => $order]);

                        return redirect()->to('/thank-you');
                    } catch (\Exception $e) {
                        throw new \Exception('Error processing order: ' . $e->getMessage());
                    }
                }),
        ];
    }
}
