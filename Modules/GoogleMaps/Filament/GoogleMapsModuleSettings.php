<?php

namespace Modules\GoogleMaps\Filament;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class GoogleMapsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'google_maps';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Options')
                    ->schema([
                        Tabs\Tab::make('Location')
                            ->schema([
                                TextInput::make('options.data-country')
                                    ->label('Country')
                                    ->live(),
                                TextInput::make('options.data-city')
                                    ->label('City')
                                    ->live(),
                                TextInput::make('options.data-street')
                                    ->label('Street')
                                    ->live(),
                                TextInput::make('options.data-zip')
                                    ->label('Zip')
                                    ->live(),
                            ]),
                        Tabs\Tab::make('Map')
                            ->schema([
                                TextInput::make('options.data-zoom')
                                    ->label('Zoom')
                                    ->numeric()
                                    ->live(),
                                TextInput::make('options.data-width')
                                    ->label('Width')
                                    ->numeric()
                                    ->live(),
                                TextInput::make('options.data-height')
                                    ->label('Height')
                                    ->numeric()
                                    ->live(),

                            ]),
                    ]),
            ]);
    }
}
