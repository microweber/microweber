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
            \Filament\Schemas\Components\Section::make()
                ->schema(function (\Filament\Schemas\Components\Section $component, \Filament\Schemas\Components\Utilities\Set $set, \Filament\Schemas\Components\Utilities\Get $get, mixed $state = null) use ($instructions) {
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
            \Filament\Schemas\Components\Section::make()
                ->statePath('settings')
                ->reactive()
                ->schema(function (\Filament\Schemas\Components\Section $component, \Filament\Schemas\Components\Utilities\Set $set, \Filament\Schemas\Components\Utilities\Get $get, mixed $state = null) {
                    $provider = $get('provider');

                    return [

                        Forms\Components\Textarea::make('shipping_instructions')
                            ->label('Shipping Instructions')
                            ->columnSpan('full')
                            ->default('')
                    ];

                })
                ->visible(function (\Filament\Schemas\Components\Utilities\Get $get) {
                    return (
                        $get('provider') === 'pickup_from_address'

                    );
                })
                ->columns(2)
            ,

        ];
    }


}
