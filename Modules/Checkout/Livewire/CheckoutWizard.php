<?php

namespace Modules\Checkout\Livewire;

use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\View;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Modules\Checkout\Services\CheckoutService;
use Modules\Order\Models\Order;

class CheckoutWizard extends Component implements \Filament\Schemas\Contracts\HasSchemas, \Filament\Actions\Contracts\HasActions
{
    use InteractsWithSchemas;
    use \Filament\Actions\Concerns\InteractsWithActions;

    public ?array $data = [];

    #[Url]
    public int $step = 1;

    public array $cartItems = [];
    public float $cartTotal = 0;
    public array $shippingMethods = [];
    public array $paymentMethods = [];
    public ?Order $order = null;

    public function mount(): void
    {
        $this->loadCartData();
        $this->loadShippingMethods();
        $this->loadPaymentMethods();

        // Check if cart is empty
        if (empty($this->cartItems)) {
            $this->redirect(site_url());
            return;
        }

        // Initialize form with existing checkout data
        $checkoutData = checkout_get_user_info() ?: [];

        // If user is logged in, pre-fill with user data
        if (is_logged()) {
            $user = get_user();
            $checkoutData = array_merge([
                'first_name' => $user['first_name'] ?? '',
                'last_name' => $user['last_name'] ?? '',
                'email' => $user['email'] ?? '',
                'phone' => $user['phone'] ?? '',
            ], $checkoutData);

            // Load saved shipping address if available
            $shippingAddress = app()->user_manager->get_shipping_address();
            if (!empty($shippingAddress)) {
                $checkoutData = array_merge($checkoutData, [
                    'address' => $shippingAddress['address'] ?? '',
                    'city' => $shippingAddress['city'] ?? '',
                    'state' => $shippingAddress['state'] ?? '',
                    'postal_code' => $shippingAddress['zip'] ?? '',
                    'country' => $shippingAddress['country'] ?? '',
                ]);
            }
        }

        $this->form->fill($checkoutData);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Wizard::make($this->getWizardSteps())
                    ->startOnStep($this->step)
                    ->contained()
                    ->persistStepInQueryString('step')
            ->submitAction(new HtmlString(''))
            ->cancelAction('cancel'),
            ])
            ->statePath('data');
    }

    protected function getWizardSteps(): array
    {
        return [
            Step::make('cart')
                ->label(__('Cart Review'))
                ->icon('heroicon-o-shopping-cart')
                ->schema($this->getCartStepSchema()),

            Step::make('contact')
                ->label(__('Contact Information'))
                ->icon('heroicon-o-user')
                ->schema($this->getContactStepSchema()),

            Step::make('shipping')
                ->label(__('Shipping'))
                ->icon('heroicon-o-truck')
                ->schema($this->getShippingStepSchema()),

            Step::make('payment')
                ->label(__('Payment'))
                ->icon('heroicon-o-credit-card')
                ->schema($this->getPaymentStepSchema()),

            Step::make('review')
                ->label(__('Review & Confirm'))
                ->icon('heroicon-o-clipboard-document-check')
                ->schema($this->getReviewStepSchema()),
        ];
    }

    protected function getCartStepSchema(): array
    {
        return [
            Section::make(__('Your Cart'))
                ->description(__('Review your items before proceeding'))
                ->schema([
                    View::make('modules.checkout::livewire.checkout-wizard.cart-items')
                        ->viewData([
                            'cartItems' => $this->cartItems,
                            'cartTotal' => $this->cartTotal,
                        ]),
                ]),
        ];
    }

    protected function getContactStepSchema(): array
    {
        return [
            Section::make(__('Contact Information'))
                ->description(__('We need your details to process your order'))
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('first_name')
                                ->label(__('First Name'))
                                ->required()
                                ->maxLength(255)
                                ->placeholder(__('Enter your first name')),

                            TextInput::make('last_name')
                                ->label(__('Last Name'))
                                ->required()
                                ->maxLength(255)
                                ->placeholder(__('Enter your last name')),
                        ]),

                    TextInput::make('email')
                        ->label(__('Email'))
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->placeholder(__('your@email.com'))
                        ->helperText(__('We will send order confirmation to this email')),

                    TextInput::make('phone')
                        ->label(__('Phone Number'))
                        ->tel()
                        ->required()
                        ->maxLength(255)
                        ->placeholder(__('+1 234 567 890')),
                ]),

            Section::make(__('Shipping Address'))
                ->description(__('Where should we deliver your order?'))
                ->schema([
                    Select::make('country')
                        ->label(__('Country'))
                        ->required()
                        ->searchable()
                        ->options(function () {
                            return app()->country_manager->getCountries();
                        }),

                    Grid::make(2)
                        ->schema([
                            TextInput::make('city')
                                ->label(__('City'))
                                ->required()
                                ->maxLength(255),

                            TextInput::make('state')
                                ->label(__('State / Province'))
                                ->required()
                                ->maxLength(255),
                        ]),

                    Grid::make(2)
                        ->schema([
                            TextInput::make('postal_code')
                                ->label(__('Postal / ZIP Code'))
                                ->required()
                                ->maxLength(20),

                            TextInput::make('address')
                                ->label(__('Street Address'))
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),
                        ]),
                ]),

            // Guest checkout option - show if not logged in
            Section::make(__('Account Options'))
                ->visible(fn () => !is_logged())
                ->schema([
                    Checkbox::make('create_account')
                        ->label(__('Create an account'))
                        ->helperText(__('Save your details for faster checkout next time'))
                        ->live(),

                    TextInput::make('password')
                        ->label(__('Password'))
                        ->password()
                        ->required(fn (Get $get) => $get('create_account'))
                        ->visible(fn (Get $get) => $get('create_account'))
                        ->minLength(8)
                        ->confirmed(),

                    TextInput::make('password_confirmation')
                        ->label(__('Confirm Password'))
                        ->password()
                        ->required(fn (Get $get) => $get('create_account'))
                        ->visible(fn (Get $get) => $get('create_account')),
                ]),
        ];
    }

    protected function getShippingStepSchema(): array
    {
        $options = [];
        foreach ($this->shippingMethods as $method) {
            $cost = $method['cost'] ?? 0;
            $costDisplay = $cost > 0 ? ' - ' . currency_format($cost) : ' - ' . __('Free');
            $options[$method['id']] = ($method['name'] ?? ucfirst($method['provider'] ?? 'Standard')) . $costDisplay;
        }

        return [
            Section::make(__('Shipping Method'))
                ->description(__('Choose how you want your order delivered'))
                ->schema([
                    Radio::make('shipping_provider_id')
                        ->label(__('Shipping Options'))
                        ->options($options)
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state) {
                            checkout_set_user_info('shipping_provider_id', $state);
                            $this->dispatch('shipping-changed');
                        }),

                    Placeholder::make('shipping_info')
                        ->label('')
                        ->content(function (Get $get) {
                            $providerId = $get('shipping_provider_id');
                            if (!$providerId) {
                                return '';
                            }

                            $provider = app()->shipping_method_manager->getProviderById($providerId);
                            if (!$provider) {
                                return '';
                            }

                            $cost = $provider['cost'] ?? app()->shipping_method_manager->getShippingCost($providerId, []);
                            $description = $provider['description'] ?? __('Standard shipping');

                            return __('Cost: ') . currency_format($cost) . ' | ' . $description;
                        })
                        ->visible(fn (Get $get) => $get('shipping_provider_id')),
                ]),

            Section::make(__('Shipping Address Confirmation'))
                ->description(__('Verify your shipping details'))
                ->schema([
                    Placeholder::make('address_summary')
                        ->label('')
                        ->content(function (Get $get) {
                            $parts = [
                                $get('first_name') . ' ' . $get('last_name'),
                                $get('address'),
                                $get('city') . ', ' . $get('state') . ' ' . $get('postal_code'),
                                app()->country_manager->getCountryName($get('country')),
                            ];
                            return implode('<br>', array_filter($parts));
                        })
                        ->html(),
                ]),
        ];
    }

    protected function getPaymentStepSchema(): array
    {
        $options = [];
        foreach ($this->paymentMethods as $method) {
            $options[$method['id']] = $method['name'] ?? ucfirst($method['provider'] ?? 'Standard');
        }

        return [
            Section::make(__('Payment Method'))
                ->description(__('Select how you want to pay'))
                ->schema([
                    Radio::make('payment_provider_id')
                        ->label(__('Payment Options'))
                        ->options($options)
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state) {
                            checkout_set_user_info('payment_provider_id', $state);
                        }),

                    // Dynamic payment provider forms
                    Placeholder::make('payment_form_placeholder')
                        ->label('')
                        ->content(function (Get $get) {
                            $providerId = $get('payment_provider_id');
                            if (!$providerId) {
                                return '';
                            }

                            $provider = app()->payment_method_manager->getProviderById($providerId);
                            if (!$provider || !isset($provider['form_view'])) {
                                return '';
                            }

                            return view($provider['form_view'])->render();
                        })
                        ->html()
                        ->visible(fn (Get $get) => $get('payment_provider_id')),
                ]),

            Section::make(__('Order Summary'))
                ->description(__('Current totals'))
                ->schema([
                    Placeholder::make('order_summary')
                        ->label('')
                        ->content(function (Get $get) {
                            $subtotal = $this->cartTotal;
                            $shippingId = $get('shipping_provider_id');
                            $shipping = $shippingId ? app()->shipping_method_manager->getShippingCost($shippingId, []) : 0;
                            $total = $subtotal + $shipping;

                            return view('modules.checkout::livewire.checkout-wizard.order-summary', [
                                'subtotal' => $subtotal,
                                'shipping' => $shipping,
                                'total' => $total,
                            ])->render();
                        })
                        ->html(),
                ]),

            Section::make(__('Terms and Conditions'))
                ->visible(fn () => get_option('shop_require_terms', 'website') == 1)
                ->schema([
                    Checkbox::make('terms')
                        ->label(__('I agree to the terms and conditions'))
                        ->required()
                        ->accepted(),
                ]),
        ];
    }

    protected function getReviewStepSchema(): array
    {
        return [
            Section::make(__('Order Review'))
                ->description(__('Please review all details before placing your order'))
                ->schema([
                    View::make('modules.checkout::livewire.checkout-wizard.review-order')
                        ->viewData([
                            'cartItems' => $this->cartItems,
                            'cartTotal' => $this->cartTotal,
                            'data' => $this->data,
                        ]),
                ]),

            Actions::make([
                Action::make('place_order')
                    ->label(__('Place Order'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->size('lg')
                    ->action(fn () => $this->submit()),
            ])
                ->fullWidth(),
        ];
    }

    #[On('wizardStepChanged')]
    public function onStepChanged(int $step): void
    {
        $this->step = $step;

        // Save current step data to session
        $this->saveStepData();
    }

    public function saveStepData(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            if ($value !== null) {
                checkout_set_user_info($key, $value);
            }
        }
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        // Handle guest checkout with account creation
        if (!is_logged() && !empty($data['create_account'])) {
            $this->createGuestAccount($data);
        }

        // Process checkout
        try {
            $checkoutService = app(CheckoutService::class);
            $result = $checkoutService->checkout($data);

            if (isset($result['error'])) {
                $errorMessage = is_array($result['error']) ? implode(', ', $result['error']) : $result['error'];
                Notification::make()
                    ->title(__('Order Error'))
                    ->body($errorMessage)
                    ->danger()
                    ->send();
                return;
            }

            if (isset($result['redirect'])) {
                $this->redirect($result['redirect']);
                return;
            }

            // Clear checkout session data
            session_forget('checkout');

            Notification::make()
                ->title(__('Order Placed Successfully'))
                ->body(__('Your order has been placed. Order #:order_id', ['order_id' => $result['order_id'] ?? '']))
                ->success()
                ->send();

            $this->redirect(route('filament.checkout.resources.checkout.success'));

        } catch (\Exception $e) {
            Notification::make()
                ->title(__('Error Processing Order'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function cancel(): void
    {
        session_forget('checkout');
        $this->redirect(site_url());
    }

    protected function createGuestAccount(array $data): void
    {
        try {
            $userData = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? '',
                'password' => $data['password'],
                'password_confirmation' => $data['password_confirmation'],
            ];

            $user = app()->user_manager->register($userData);

            if ($user && !empty($user['id'])) {
                // Save shipping address for the user
                app()->user_manager->save_shipping_address([
                    'user_id' => $user['id'],
                    'address' => $data['address'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'zip' => $data['postal_code'],
                    'country' => $data['country'],
                ]);

                // Log the user in
                app()->user_manager->login($data['email'], $data['password']);

                Notification::make()
                    ->title(__('Account Created'))
                    ->body(__('Your account has been created successfully'))
                    ->success()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('Account Creation Failed'))
                ->body($e->getMessage())
                ->warning()
                ->send();
        }
    }

    protected function loadCartData(): void
    {
        $this->cartItems = app()->cart_manager->get() ?? [];
        $this->cartTotal = cart_total();
    }

    protected function loadShippingMethods(): void
    {
        $methods = app()->shipping_method_manager->getProviders() ?? [];
        $this->shippingMethods = array_map(function ($method) {
            $method['cost'] = app()->shipping_method_manager->getShippingCost($method['id'], []);
            return $method;
        }, $methods);
    }

    protected function loadPaymentMethods(): void
    {
        $this->paymentMethods = app()->payment_method_manager->getProviders() ?? [];
    }

    #[On('reload-cart')]
    public function reloadCart(): void
    {
        $this->loadCartData();
    }

    #[On('shipping-changed')]
    public function onShippingChanged(): void
    {
        $this->cartTotal = cart_total();
    }

    public function render()
    {
        return view('modules.checkout::livewire.checkout-wizard.index');
    }
}
