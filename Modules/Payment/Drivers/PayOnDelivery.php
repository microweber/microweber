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
            \Filament\Schemas\Components\Section::make()
                ->schema(function (\Filament\Schemas\Components\Section $component, \Filament\Schemas\Components\Utilities\Set $set, \Filament\Schemas\Components\Utilities\Get $get, mixed $state = null) use ($message) {
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
            \Filament\Schemas\Components\Section::make()
                ->statePath('settings')
                ->reactive()
                ->schema(function (\Filament\Schemas\Components\Section $component, \Filament\Schemas\Components\Utilities\Set $set, \Filament\Schemas\Components\Utilities\Get $get, mixed $state = null) {
                    $provider = $get('provider');

                    return [
                        Forms\Components\Textarea::make('payment_instructions')
                            ->label('Payment Instructions')
                            ->columnSpan('full')
                            ->default('')
                    ];

                })
                ->visible(function (\Filament\Schemas\Components\Utilities\Get $get) {
                    return (
                        $get('provider') === 'pay_on_delivery'

                    );
                })
                ->columns(2)
            ,

        ];
    }




}
