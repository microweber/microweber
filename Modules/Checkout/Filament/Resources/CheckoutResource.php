<?php

namespace Modules\Checkout\Filament\Resources;


use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Event;
use Livewire\Component;
use MicroweberPackages\Filament\Forms\Components\MwMediaBrowser;
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
                                                $livewire->dispatch('reload-cart');
                                            })
                                            ->default(fn() => checkout_get_user_info('first_name')),

                                        TextInput::make('last_name')
                                            ->required()
                                            ->maxLength(255)
                                            ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                checkout_set_user_info('last_name', $state);
                                                $livewire->dispatch('reload-cart');
                                            })
                                            ->default(fn() => checkout_get_user_info('last_name')),

                                        TextInput::make('email')
                                            ->email()
                                            ->required()
                                            ->maxLength(255)
                                            ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                checkout_set_user_info('email', $state);
                                                $livewire->dispatch('reload-cart');
                                            })
                                            ->default(fn() => checkout_get_user_info('email')),

                                        TextInput::make('phone')
                                            ->tel()
                                            ->maxLength(255)
                                            ->required()
                                            ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                checkout_set_user_info('phone', $state);
                                                $livewire->dispatch('reload-cart');
                                            })
                                            ->default(fn() => checkout_get_user_info('phone')),
                                    ])
                                    ->columns(2),

                                Section::make('Shipping Address')
                                    ->schema([
                                        Select::make('country')
                                            ->required()
                                            // ->searchable()
                                            ->native()
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Get $get, $component) {
                                                /** @var Component $livewire */
                                                $livewire = $component->getLivewire();
                                                checkout_set_user_info('country', $state);
                                                $livewire->dispatch('reload-cart');
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
                                                $livewire->dispatch('reload-cart');
                                            })
                                            ->default(fn() => checkout_get_user_info('city')),

                                        TextInput::make('state')
                                            ->required()
                                            ->maxLength(255)
                                            ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                checkout_set_user_info('state', $state);
                                                $livewire->dispatch('reload-cart');
                                            })
                                            ->default(fn() => checkout_get_user_info('state')),

                                        TextInput::make('postal_code')
                                            ->required()
                                            ->maxLength(20)
                                            ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                checkout_set_user_info('postal_code', $state);
                                                $livewire->dispatch('reload-cart');
                                            })
                                            ->default(fn() => checkout_get_user_info('postal_code')),

                                        Forms\Components\Textarea::make('address')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull()
                                            ->afterStateUpdated(function ($state, Forms\Get $get, $livewire) {
                                                checkout_set_user_info('address', $state);
                                                $livewire->dispatch('reload-cart');


                                            })
                                            ->default(fn() => checkout_get_user_info('address')),
                                    ])
                                    ->columns(2),


                                Section::make('Shipping Method')
                                    ->visible(function (Forms\Get $get) {
                                        $visible = app()->shipping_method_manager->getProviders() && app()->cart_manager->get();
                                        return $visible;
                                    })
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
                                                ->required()
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

                                Section::make('Apply Coupon')
                                    ->visible(function (Forms\Get $get) {
                                        return app()->cart_manager->get() and coupon_get_count();
                                    })
                                    ->schema([
                                        Forms\Components\Placeholder::make('coupon_code')
                                            ->hidden(fn() => !coupons_get_session())
                                            ->label('Coupon Code')
                                            ->content(function () {
                                                $coupon = coupons_get_session();
                                                if ($coupon) {
                                                    return 'Active coupon: ' . $coupon['coupon_code'];
                                                }
                                                return '';
                                            }),

                                        Forms\Components\Actions::make([
                                            Action::make('apply_coupon')
                                                ->label('Apply Coupon')
                                                ->button()
                                                ->hidden(fn() => coupons_get_session())
                                                ->modalHeading('Apply Coupon')
                                                ->modalDescription('Enter your coupon code to get a discount')
                                                ->modalSubmitActionLabel('Apply Coupon')
                                                ->modalCancelActionLabel('Cancel')
                                                ->form([
                                                    TextInput::make('coupon_code')
                                                        ->label('Coupon Code')
                                                        ->placeholder('Enter your coupon code')
                                                        ->required()
                                                        ->maxLength(255)
                                                ])
                                                ->action(function (array $data, $livewire) {
                                                    if (empty($data['coupon_code'])) {
                                                        return;
                                                    }

                                                    $result = coupon_apply([
                                                        'coupon_code' => $data['coupon_code']
                                                    ]);

                                                    if (isset($result['error'])) {
                                                        $livewire->addError('coupon_code', $result['message'] ?? 'Invalid coupon code');
                                                        return;
                                                    }

                                                    $livewire->dispatch('reload-cart');
                                                }),

                                            Action::make('remove_coupon')
                                                ->label('Remove Coupon')
                                                ->visible(fn() => coupons_get_session())
                                                ->button()
                                                ->color('danger')
                                                ->action(function ($state, $livewire) {
                                                    coupons_delete_session();
                                                    $livewire->dispatch('reload-cart');
                                                }),
                                        ])->columnSpanFull(),
                                    ]),

                                Section::make('Payment Method')
                                    ->visible(function (Forms\Get $get) {
                                        $visible = app()->shipping_method_manager->getProviders() && app()->cart_manager->get();
                                        return $visible;
                                    })
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
                                                ->required()
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
