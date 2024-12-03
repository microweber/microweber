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


    public function render(): string
    {

        $model = $this->getModel();
        $paymentInstructions = $model->settings['payment_instructions'] ?? '';

        return view('modules.payment::providers.pay_on_delivery', compact('paymentInstructions'));
    }

}
