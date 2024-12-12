<?php

namespace Modules\Shipping\Drivers;

use Filament\Forms;


class PickupFromAddress extends AbstractShippingMethod
{
    public string $provider = 'pickup_from_address';

    public function title(): string
    {
        return 'Pickup From Address';
    }

    public function getShippingCost($data = []): float|int
    {
        return 0;
    }

    public function getForm(): array
    {
        $instructions = $this->getModel()->settings['shipping_instructions'] ?? '';

        if (!$instructions) {
            return [];
        }

        return [
            Forms\Components\Section::make()
                ->schema(function (Forms\Components\Section $component, Forms\Set $set, Forms\Get $get, ?array $state) use ($instructions) {
                    return [
                        Forms\Components\Placeholder::make('')
                            ->content($instructions)
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


}
