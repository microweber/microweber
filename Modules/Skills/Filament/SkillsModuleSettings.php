<?php

namespace Modules\Skills\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Forms;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;


class SkillsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'skills';
    public array $skills;

    public function mount(): void
    {
        parent::mount();
        $this->skills = @json_decode($this->getOption('skills'), true) ?? [];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('skills')
                    ->deleteAction(
                        function (Forms\Get $get, Forms\Set $set) {
                            $this->skills = array_values($this->skills);
                            $set('skills', $this->skills);
                        }
                    )
                    ->label('Skills')
                    ->schema([
                        TextInput::make('skill')
                            ->label('Skill Name')
                            ->placeholder('Insert skill name')
                            ->live(),

                        TextInput::make('percent')
                            ->label('Value (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(1)
                            ->live(),

                        Select::make('style')
                            ->label('Style')
                            ->options([
                                'primary' => 'Primary',
                                'warning' => 'Warning',
                                'danger' => 'Danger',
                                'success' => 'Success',
                                'info' => 'Info',
                            ])
                            ->live(),
                    ])
                    ->minItems(1)
                    ->live()
            ]);
    }

    public function updated($propertyName, $value): void
    {
        parent::updated($propertyName, $value);
        $this->saveOption('skills', json_encode($this->skills));
    }

    public function save(): void
    {
        parent::save();
        $this->saveOption('skills', json_encode($this->skills));
    }
}
