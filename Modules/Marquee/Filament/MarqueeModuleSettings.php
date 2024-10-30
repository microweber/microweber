<?php

namespace Modules\Marquee\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class MarqueeModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'marquee';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('options.text')
                    ->label('Marquee Text')
                    ->helperText('Enter the text for the marquee.')
                    ->live()
                    ->default('Your cool text here!'),

                TextInput::make('options.fontSize')
                    ->label('Font Size')
                    ->helperText('Enter the font size for the marquee text.')
                    ->live()
                    ->default('46'),

                TextInput::make('options.animationSpeed')
                    ->label('Animation Speed')
                    ->helperText('Enter the animation speed for the marquee.')
                    ->live()
                    ->default('normal'),

                TextInput::make('options.textWeight')
                    ->label('Text Weight')
                    ->helperText('Enter the text weight for the marquee text.')
                    ->live()
                    ->default('normal'),

                TextInput::make('options.textStyle')
                    ->label('Text Style')
                    ->helperText('Enter the text style for the marquee text.')
                    ->live()
                    ->default('normal'),

                TextInput::make('options.textColor')
                    ->label('Text Color')
                    ->helperText('Enter the text color for the marquee text.')
                    ->live()
                    ->default('#000000'),
            ]);
    }
}
