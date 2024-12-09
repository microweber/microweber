<?php

namespace Modules\Payment\Drivers;

use Filament\Forms;


class PayOnDelivery extends AbstractPaymentMethod
{
    public string $provider = 'pay_on_delivery';

    public function title(): string
    {
        return 'Pay on delivery';
    }

    public function logo(): string
    {
        return asset('modules/payment/img/pay_on_delivery.png');
    }


    public function process($data = [])
    {
        return [
            'success' => true,
            'status' => 'pending',
         ];
    }

    public function getForm(): array
    {
        $model = $this->getModel();
        $message = $model->settings['payment_instructions'] ?? 'Pay on delivery';

        return [
            Forms\Components\Section::make()
                ->schema(function (Forms\Components\Section $component, Forms\Set $set, Forms\Get $get, ?array $state) use ($message) {
                    return [
                        Forms\Components\Placeholder::make('')
                            ->content($message)
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
                        Forms\Components\Textarea::make('payment_instructions')
                            ->label('Payment Instructions')
                            ->columnSpan('full')
                            ->default('')
                    ];

                })
                ->visible(function (Forms\Get $get) {
                    return (
                        $get('provider') === 'pay_on_delivery'

                    );
                })
                ->columns(2)
            ,

        ];
    }




}
