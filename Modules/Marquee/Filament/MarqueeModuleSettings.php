<?php

namespace Modules\Marquee\Filament;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use MicroweberPackages\Filament\Forms\Components\MwColorPicker;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class MarqueeModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'marquee';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('options.text')
                    ->label('Marquee Text')
                    ->helperText('Enter the text for the marquee.')
                    ->live()
                    ->default(fn () => $this->getOption('text', 'Your cool text here!')),

                TextInput::make('options.fontSize')
                    ->label('Font Size')
                    ->helperText('Enter the font size for the marquee text.')
                    ->live()
                    ->numeric()
                    ->default(fn () => $this->getOption('fontSize', 46)),

                TextInput::make('options.animationSpeed')
                    ->label('Animation Speed')
                    ->helperText('Enter the animation speed for the marquee.')
                    ->numeric()
                    ->live()
                    ->default(fn () => $this->getOption('animationSpeed', 100)),

                Select::make('options.textWeight')
                    ->label('Text Weight')
                    ->helperText('The font weight of the marquee text.')
                    ->options([
                        'normal' => 'Normal',
                        'bold' => 'Bold',
                    ])
                    ->live()
                    ->default(fn () => $this->getOption('textWeight', 'normal')),

                Select::make('options.textStyle')
                    ->label('Text Style')
                    ->helperText('The font style of the marquee text.')
                    ->options([
                        'normal' => 'Normal',
                        'italic' => 'Italic',
                    ])
                    ->live()
                    ->default(fn () => $this->getOption('textStyle', 'normal')),

                MwColorPicker::make('options.textColor')
                    ->label('Text Color')
                    ->helperText('The color of the marquee text.')
                    ->live()
                    ->default(fn () => $this->getOption('textColor', '#000000')),
            ]);
    }
}
