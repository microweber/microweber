<?php

namespace Modules\GoogleMaps\Filament;

use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
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
                        // task-2026-05-22-slice2-ai872 / AI-872 Slice 2 — GoogleMaps: zoom select + map type + marker
                        // AI-1017 / task-2026-05-22 — fixed key mismatch: was `data-maptype` (no hyphen),
                        // render() reads `data-map-type` (with hyphen). Key changed to `data-map-type`.
                        // AI-1019 / task-2026-05-22 — added API key field (Phase 1 placeholder).
                        // AI-1020 / task-2026-05-22 — added marker label TextInput.
                        Tabs\Tab::make('Map')
                            ->schema([
                                Select::make('options.data-zoom')
                                    ->label('Zoom')
                                    ->live()
                                    ->options([
                                        '5'  => 'Country (5)',
                                        '10' => 'City (10)',
                                        '12' => 'District (12)',
                                        '15' => 'Street (15)',
                                        '18' => 'Building (18)',
                                    ])
                                    ->default('12'),

                                Select::make('options.data-map-type')
                                    ->label('Map type')
                                    ->live()
                                    ->options([
                                        'roadmap'  => 'Road map',
                                        'satellite' => 'Satellite',
                                        'terrain'  => 'Terrain',
                                        'hybrid'   => 'Hybrid',
                                    ])
                                    ->default('roadmap'),

                                Toggle::make('options.data-show-marker')
                                    ->label('Show marker')
                                    ->live()
                                    ->default(true)
                                    ->helperText('Display a pin at the specified location.'),

                                TextInput::make('options.data-marker-label')
                                    ->label('Marker label')
                                    ->live()
                                    ->maxLength(1)
                                    ->placeholder('A')
                                    ->helperText('Single character shown on the marker pin (optional).'),

                                TextInput::make('options.data-width')
                                    ->label('Width')
                                    ->numeric()
                                    ->live(),
                                TextInput::make('options.data-height')
                                    ->label('Height')
                                    ->numeric()
                                    ->live(),

                            ]),

                        Tabs\Tab::make('Advanced')
                            ->schema([
                                TextInput::make('options.data-api-key')
                                    ->label('Google Maps API Key')
                                    ->live()
                                    ->password()
                                    ->revealable()
                                    ->helperText('Optional: required for Maps JavaScript API features (satellite, terrain). Leave blank to use the free embed URL.'),
                            ]),
                    ]),
            ]);
    }
}
