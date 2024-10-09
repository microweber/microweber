<?php

namespace Modules\Shipping\Drivers;

use Filament\Forms;


class PickupFromAddress extends AbstractShippingMethod
{

    public function title(): string
    {
        return 'Pickup From Address';
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

                        Forms\Components\Textarea::make('shipping_instructions')
                            ->label('Shipping Instructions')
                            ->columnSpan('full')
                            ->default('')
                    ];

                })
                ->visible(function (Forms\Get $get) {
                    return (
                        $get('provider') === 'pickup_from_address'

                    );
                })
                ->columns(2)
            ,

        ];
    }


    public function view(): string
    {
        return 'shipping::flat_rate';
    }

}
