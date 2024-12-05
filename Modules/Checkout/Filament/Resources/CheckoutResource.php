<?php

namespace Modules\Checkout\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ViewField;
use Modules\Checkout\Filament\Actions\PaymentAction;
use Illuminate\Support\Facades\Event;
use Modules\Checkout\Livewire\ReviewOrder;

class CheckoutResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Checkout';
    protected static ?string $modelLabel = 'Checkout';
    protected static ?string $slug = 'checkout';

    public static function form(Form $form): Form
    {
        $cartItems = app()->cart_manager->get() ?? [];

        return $form
            ->schema([
                Wizard::make([
                    Step::make('Contact Information')
                        ->schema([
                            TextInput::make('first_name')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('amount')
                                ->required()
                                ->default(cart_total())
                                ->maxLength(255),
                            TextInput::make('last_name')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->tel()
                                ->required(),
                        ]),

                    Step::make('Shipping Information')
                        ->schema([
                            TextInput::make('address')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('city')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('state')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('postal_code')
                                ->required()
                                ->maxLength(20),
                            Select::make('country')
                                ->required()
                                ->options(function () {
                                    return app()->country_manager->get_countries();
                                }),
                        ]),

                    Step::make('Review & Payment')
                        ->schema([
                            Repeater::make('cart_items')
                                ->schema([
                                    ViewField::make('image')
                                        ->view('modules.checkout::components.cart-item-image')
                                        ->columnSpan(1),
                                    TextInput::make('title')
                                        ->label('Product')
                                        ->disabled()
                                        ->columnSpan(2),
                                    TextInput::make('price')
                                        ->disabled()
                                        ->columnSpan(1),
                                    TextInput::make('qty')
                                        ->label('Quantity')
                                        ->numeric()
                                        ->live()
                                        ->afterStateUpdated(function ($state, Forms\Get $get,  $livewire) {
                                            $record = ($get('./'));

                                            if ($record && isset($record['id'])) {
                                                $record['qty'] = $state;
                                                app()->cart_manager->update_item_qty($record);
                                                $livewire->dispatch('cart-updated');
                                            }
                                        })
                                        ->columnSpan(1),
                                    Actions::make([
                                        Action::make('remove')
                                            ->label('Remove')
                                            ->color('danger')
                                            ->icon('heroicon-m-trash')
                                            ->action(function ($state, $livewire) {

                                                if ($state && isset($state['id'])) {

                                                    app()->cart_manager->remove_item($state['id']);
                                                    // Dispatch event to refresh the Livewire component
                                                    $livewire->dispatch('cart-updated');
                                                }
                                            })
                                    ])
                                    ->columnSpan(1),
                                ])
                                ->disabled(false)
                                ->addable(false)
                                ->deletable(false)
                                ->reorderable(false)
                                ->columns(6)
                                ->default(function () use ($cartItems) {
                                    if (!$cartItems) {
                                        return [];
                                    }

                                    return array_map(function($item) {
                                        return [
                                            'id' => $item['id'],
                                            'title' => $item['title'],
                                            'price' => $item['price'],
                                            'qty' => $item['qty'],
                                            'image' => $item['picture'] ?? null
                                        ];
                                    }, $cartItems);
                                })
                                ->columnSpanFull(),

                            Forms\Components\Livewire::make('review-order')
                                ->component(ReviewOrder::class)
                                ->columnSpanFull(),
                        ])
                        ->extraAttributes(['class' => 'space-y-6'])
                        ->afterValidation(function (array $data) {
                            // Save contact and shipping information
                            app()->user_manager->session_set('checkout_first_name', $data['first_name']);
                            app()->user_manager->session_set('checkout_last_name', $data['last_name']);
                            app()->user_manager->session_set('checkout_email', $data['email']);
                            app()->user_manager->session_set('checkout_phone', $data['phone']);
                            app()->user_manager->session_set('checkout_address', $data['address']);
                            app()->user_manager->session_set('checkout_city', $data['city']);
                            app()->user_manager->session_set('checkout_state', $data['state']);
                            app()->user_manager->session_set('checkout_postal_code', $data['postal_code']);
                            app()->user_manager->session_set('checkout_country', $data['country']);
                        }),
                ])
                    ->columnSpan('full')
                    ->persistStepInQueryString()
                    ->skippable(false)
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
