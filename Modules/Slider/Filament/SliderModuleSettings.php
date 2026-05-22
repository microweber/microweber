<?php

namespace Modules\Slider\Filament;

use Filament\Schemas\Components\Livewire;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettingsTable;
use Modules\Slider\Models\Slider;

class SliderModuleSettings extends LiveEditModuleSettingsTable
{
    public string $module = 'slider';
    public string $modelName = Slider::class;
    public string $tableComponentName = SliderTableList::class;

    public function form(Schema $schema): Schema
    {
        $module_id = $this->params['id'] ?? null;
        $rel_id = $this->getOption('rel_id') ?? $this->params['rel_id'] ?? $this->params['id'] ?? null;
        $rel_type = $this->getOption('rel_type') ?? $this->params['rel_type'] ?? 'module';

        return $schema
            ->schema([
                Tabs::make('Slider')
                    ->schema([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Livewire::make($this->tableComponentName, [
                                    'rel_id' => $rel_id,
                                    'rel_type' => $rel_type,
                                    'module_id' => $module_id,
                                ])
                            ]),
                        // task-2026-05-22-slice2-ai872 / AI-872 Slice 2 — Slider: playback controls
                        Tabs\Tab::make('Playback')
                            ->schema([
                                Toggle::make('options.autoplay')
                                    ->label('Autoplay')
                                    ->live()
                                    ->helperText('Automatically advance slides without user interaction.'),

                                Select::make('options.autoplay_speed')
                                    ->label('Speed')
                                    ->live()
                                    ->options([
                                        '2000' => '2 seconds',
                                        '3000' => '3 seconds',
                                        '5000' => '5 seconds',
                                        '8000' => '8 seconds',
                                    ])
                                    ->default('3000')
                                    ->helperText('Delay between automatic slide transitions.'),

                                Toggle::make('options.show_arrows')
                                    ->label('Navigation arrows')
                                    ->live()
                                    ->default(true)
                                    ->helperText('Show previous / next arrow buttons on the slider.'),

                                Toggle::make('options.show_dots')
                                    ->label('Pagination dots')
                                    ->live()
                                    ->default(true)
                                    ->helperText('Show dot indicators for each slide.'),
                            ]),
                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                        Tabs\Tab::make('Advanced')
                            ->schema($this->getDataSourceFormSchema()),
                    ]),
            ]);
    }
}
