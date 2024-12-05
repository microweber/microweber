<?php

namespace Modules\Payment\Drivers;

use Filament\Forms;


class Stripe extends AbstractPaymentMethod
{
    public string $provider = 'stripe';

    public function logo(): string
    {
        return asset('modules/payment/img/stripe.png');
    }

    public function title(): string
    {
        return 'Stripe';
    }

    public function process($data = [])
    {
        return [
            'success' => true,
            // 'redirect' => route('checkout.success')
        ];
    }

    public function getForm(): array
    {
        return [
            Forms\Components\Section::make()
                ->schema(function (Forms\Components\Section $component, Forms\Set $set, Forms\Get $get, ?array $state) {
                    return [
                        Forms\Components\Placeholder::make('')
                            ->content('You will be redirected to Stripe to complete your payment.'),
                    ];
                })
        ];
    }

    public function getSettingsForm(): array
    {
        return [
            Forms\Components\Section::make()
                ->statePath('settings')
                ->reactive()
                ->schema(function (Forms\Components\Section $component, Forms\Set $set, Forms\Get $get, ?array $state) {
                    $provider = $get('provider');

                    return [
                        Forms\Components\TextInput::make('stripe_publishable_key')
                            ->label('Publishable Key')
                            ->columnSpan('full')
                            ->placeholder('Enter your Stripe publishable key')
                            ->default(''),
                        Forms\Components\TextInput::make('stripe_secret_key')
                            ->label('Secret Key')
                            ->columnSpan('full')
                            ->placeholder('Enter your Stripe secret key')
                            ->default(''),
                    ];
                })
                ->visible(function (Forms\Get $get) {
                    return (
                        $get('provider') === 'stripe'
                    );
                })


        ];
    }




}
