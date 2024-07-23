<?php

namespace MicroweberPackages\Payment\Providers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class PayPal extends \MicroweberPackages\Payment\PaymentMethod
{

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

    public function getSettingsForm($form): array
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
                            ->default(1)
                            ->required(),

                    ];

                })
                ->visible(function (Forms\Get $get) {
                    return (
                        $get('provider') === 'paypal'
                    );
                })


        ];
    }


    public function view(): string
    {
        return 'payment::paypal';
    }

}
