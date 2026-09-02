<?php

namespace Modules\GoogleMaps\Filament;

use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
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

                                // AI-1018 / task-2026-06-05-AI1018 — live map preview.
                                // The address fields above are ->live(), so editing
                                // any of them triggers a Livewire round-trip that
                                // re-renders this Placeholder, updating the embedded
                                // map src. Server-reactive (rather than the ticket's
                                // Alpine x-bind:src) so it composes cleanly with
                                // Filament's form state and needs no client glue.
                                // Removes the slow type→save→navigate→check loop:
                                // the operator verifies the geocoded location in the
                                // panel before saving.
                                Placeholder::make('map_preview')
                                    ->label('Map preview')
                                    ->columnSpanFull()
                                    ->content(function (Get $get): HtmlString {
                                        $parts = array_values(array_filter([
                                            $get('options.data-street'),
                                            $get('options.data-city'),
                                            $get('options.data-zip'),
                                            $get('options.data-country'),
                                        ], static fn ($v) => filled($v)));

                                        if (empty($parts)) {
                                            return new HtmlString(
                                                '<div class="mw-map-preview-empty" style="padding:14px;border:1px dashed var(--gray-300,#d1d5db);border-radius:8px;color:var(--gray-500,#6b7280);font-size:.8125rem;">'
                                                . e(__('Enter an address above to preview the map.'))
                                                . '</div>'
                                            );
                                        }

                                        $zoom = (int) ($get('options.data-zoom') ?: 12);
                                        if ($zoom < 1 || $zoom > 21) {
                                            $zoom = 12;
                                        }
                                        $query = rawurlencode(implode(', ', $parts));
                                        $src = 'https://maps.google.com/maps?q=' . $query . '&z=' . $zoom . '&output=embed';

                                        return new HtmlString(
                                            '<iframe class="mw-map-preview-iframe" src="' . e($src) . '"'
                                            . ' loading="lazy" referrerpolicy="no-referrer-when-downgrade"'
                                            . ' title="' . e(__('Map preview')) . '"'
                                            . ' style="width:100%;height:220px;border:1px solid var(--gray-200,#e5e7eb);border-radius:8px;"></iframe>'
                                        );
                                    }),
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
                                    ->default(fn () => $this->getOption('data-zoom', '12')),

                                Select::make('options.data-map-type')
                                    ->label('Map type')
                                    ->live()
                                    ->options([
                                        'roadmap'  => 'Road map',
                                        'satellite' => 'Satellite',
                                        'terrain'  => 'Terrain',
                                        'hybrid'   => 'Hybrid',
                                    ])
                                    ->default(fn () => $this->getOption('data-map-type', 'roadmap')),

                                Toggle::make('options.data-show-marker')
                                    ->label('Show marker')
                                    ->live()
                                    ->default(fn () => filter_var($this->getOption('data-show-marker', true), FILTER_VALIDATE_BOOLEAN))
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
