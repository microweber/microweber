<?php

namespace MicroweberPackages\Payment\Drivers;

use Filament\Forms;


class Stripe extends \MicroweberPackages\Payment\PaymentMethod
{

    public function logo(): string
    {
        return public_asset('vendor/microweber-packages/payment/img/stripe.png');
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

    public function getSettingsForm($form): array
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


    public function view(): string
    {
        return 'payment::stripe';
    }

}
