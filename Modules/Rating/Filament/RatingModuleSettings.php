<?php

namespace Modules\Rating\Filament;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Livewire;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettingsTable;
use Modules\Rating\Models\Rating;

class RatingModuleSettings extends LiveEditModuleSettingsTable
{
    public string $module = 'rating';
    public string $modelName = Rating::class;
    public string $tableComponentName = RatingTableList::class;

    public function form(Schema $schema): Schema
    {
         return $schema
            ->schema([
                Tabs::make('Rating')
                    ->schema([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Livewire::make(RatingTableList::class, [
                                    'rel_id' => $this->params['rel_id'] ?? $this->params['rel-id'] ?? $this->params['id'] ?? null,
                                    'rel_type' => $this->params['rel_type'] ?? $this->params['rel-type'] ?? 'module',
                                ])
                            ]),
                        Tabs\Tab::make('Design')
                            ->schema([
                                \Filament\Forms\Components\ColorPicker::make('options.starColor')
                                    ->label('Star Color')
                                    ->live()
                                    ->default('#FFD700'),
                                \Filament\Forms\Components\ColorPicker::make('options.starBgColor')
                                    ->label('Star Background Color')
                                    ->live()
                                    ->default('#ffffff00'),
                                \Filament\Forms\Components\TextInput::make('options.starSize')
                                    ->label('Star Size (px)')
                                    ->live()
                                    ->numeric()
                                    ->default(24),
                            ]),
                    ]),
            ]);
    }
}
