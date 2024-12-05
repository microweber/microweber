<?php

namespace Modules\Payment\Drivers;

use Filament\Forms;


class PayPal extends AbstractPaymentMethod
{

    public string $provider = 'paypal';
    public function logo(): string
    {
        return asset('modules/payment/img/paypal.png');
    }


    public function title(): string
    {
        return 'PayPal';
    }

    public function process($data = [])
    {
        return [
            'success' => true,
            // 'redirect' => route('checkout.success')
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
                        Forms\Components\TextInput::make('paypal_email')
                            ->label('Paypal Email')
                            ->type('email')
                            ->columnSpan('full')
                            ->placeholder('Enter your PayPal email address')
                            ->default(''),
                        Forms\Components\Toggle::make('paypal_test_mode')
                            ->label('Test Mode')
                            ->columnSpan('full')

                        ,

                    ];

                })
                ->visible(function (Forms\Get $get) {
                    return (
                        $get('provider') === 'paypal'
                    );
                })


        ];
    }

    public function getForm(): array
    {
        return [
            Forms\Components\Section::make()
                ->schema(function (Forms\Components\Section $component, Forms\Set $set, Forms\Get $get, ?array $state) {
                    return [
                        Forms\Components\Placeholder::make('')
                            ->content('You will be redirected to PayPal to complete your purchase.')
                    ];
                })
        ];
    }


}
