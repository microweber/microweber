<?php

namespace Modules\TextType\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class TextTypeModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'text_type';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Text animation')->schema([
                TextInput::make('options.text')
                    ->label('Text')
                    ->helperText('Enter the text for the animation.')
                    ->live()
                    ->default(fn () => $this->getOption('text', 'Your cool text here!')),

                TextInput::make('options.fontSize')
                    ->label('Font Size')
                    ->helperText('Enter the font size for the text.')
                    ->live()
                    ->numeric()
                    ->default(fn () => $this->getOption('fontSize', 24)),

                TextInput::make('options.animationSpeed')
                    ->label('Animation Speed')
                    ->helperText('Enter the animation speed for the text.')
                    ->numeric()
                    ->live()
                    ->default(fn () => $this->getOption('animationSpeed', '50')), // Default speed value


                ColorPicker::make('options.textColor')
                    ->label('Text Color')
                    ->helperText('The color of the animated text.')
                    ->live()
                    ->default(fn () => $this->getOption('textColor', '#000000')),
                ]),
            ]);
    }
}
