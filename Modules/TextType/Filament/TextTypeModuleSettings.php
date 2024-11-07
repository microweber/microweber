<?php

namespace Modules\TextType\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class TextTypeModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'text_type';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('options.text')
                    ->label('Text')
                    ->helperText('Enter the text for the animation.')
                    ->live()
                    ->default('Your cool text here!'),

                TextInput::make('options.fontSize')
                    ->label('Font Size')
                    ->helperText('Enter the font size for the text.')
                    ->live()
                    ->numeric()
                    ->default(46),

                TextInput::make('options.animationSpeed')
                    ->label('Animation Speed')
                    ->helperText('Enter the animation speed for the text.')
                    ->numeric()
                    ->live()
                    ->default(100), // Default speed value

                // Other options...
            ]);
    }
}
