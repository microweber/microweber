<?php

namespace Modules\Slider\Filament;

use Filament\Schemas\Components\Livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
    protected static bool $useMwDialog = true;
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
                                    ->default(fn () => filter_var($this->getOption('autoplay', true), FILTER_VALIDATE_BOOLEAN))
                                    ->helperText('Automatically advance slides without user interaction.'),

                                // AI-1013 / task-2026-05-22 — replaced preset Select with free TextInput
                                TextInput::make('options.autoplay_speed')
                                    ->label('Speed (milliseconds)')
                                    ->live()
                                    ->numeric()
                                    ->minValue(500)
                                    ->maxValue(30000)
                                    ->step(500)
                                    ->default(fn () => $this->getOption('autoplay_speed', '3000'))
                                    ->helperText('Delay in ms between slides (e.g. 3000 = 3 seconds). Minimum 500ms.'),

                                // AI-1014 / task-2026-05-22 — loop Toggle with default true
                                Toggle::make('options.loop')
                                    ->label('Loop')
                                    ->live()
                                    ->default(fn () => filter_var($this->getOption('loop', true), FILTER_VALIDATE_BOOLEAN))
                                    ->helperText('Return to first slide after the last slide.'),

                                // AI-1015 / task-2026-05-22 — transition effect selector
                                Select::make('options.effect')
                                    ->label('Transition effect')
                                    ->live()
                                    ->options([
                                        'slide'      => 'Slide',
                                        'fade'       => 'Fade',
                                        'coverflow'  => 'Coverflow',
                                    ])
                                    ->default(fn () => $this->getOption('effect', 'slide'))
                                    ->helperText('Animation style when advancing to the next slide.'),

                                Toggle::make('options.show_arrows')
                                    ->label('Navigation arrows')
                                    ->live()
                                    ->default(true)
                                    ->helperText('Show previous / next arrow buttons on the slider.'),

                                Toggle::make('options.show_dots')
                                    ->label('Pagination dots')
                                    ->live()
                                    ->default(fn () => filter_var($this->getOption('show_dots', true), FILTER_VALIDATE_BOOLEAN))
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
