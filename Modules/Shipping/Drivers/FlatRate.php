<?php

namespace Modules\Shipping\Drivers;

use Filament\Forms;


class FlatRate extends AbstractShippingMethod
{
    public string $provider = 'flat_rate';

    public function title(): string
    {
        return 'Flat Rate';
    }

    public function getShippingCost($data = []): float
    {
        return 0;
    }

    public function getForm(): array
    {
        return [
            Forms\Components\Section::make()
                ->schema(function (Forms\Components\Section $component, Forms\Set $set, Forms\Get $get, ?array $state) {
                    return [
                        Forms\Components\Placeholder::make('')
                            ->content('You have selected the Flat Rate shipping method. This method charges a fixed rate for shipping.')
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
                        Forms\Components\TextInput::make('shipping_cost')
                            ->label('Shipping Cost')
                            ->numeric()
                            ->columnSpan('full')
                            ->default(0),
                        Forms\Components\Textarea::make('shipping_instructions')
                            ->label('Shipping Instructions')
                            ->columnSpan('full')
                            ->default('')
                    ];

                })
                ->visible(function (Forms\Get $get) {
                    return (
                        $get('provider') === 'flat_rate'

                    );
                })
                ->columns(2)
            ,

        ];
    }


}
