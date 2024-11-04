<?php

namespace Modules\Skills\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class SkillsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'skills';
    public array $skills;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('options')
                    ->statePath('skills')

                    ->label('Skills')
                    ->schema([
                        TextInput::make('skill_name')
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
                    ->live(),
            ]);
    }


    public function updatedSkills($skills)
    {
     //   dd($this->skills);
    }
}
