<?php

namespace Modules\FacebookLike\Filament;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class FacebookLikeModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'facebook_like';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('options.layout')
                    ->label('Layout')
                    ->options([
                        'standard' => 'Standard',
                        'button_count' => 'Button count',
                        'button' => 'Button',
                        'box_count' => 'Box count',
                    ])
                    ->default('standard')
                    ->live(),

                Select::make('options.color')
                    ->label('Color Scheme')
                    ->options([
                        'light' => 'Light',
                        'dark' => 'Dark',
                    ])
                    ->default('light')
                    ->live(),

                Toggle::make('options.show_faces')
                    ->label('Show Faces')
                    ->default(true)
                    ->live(),

                TextInput::make('options.url')
                    ->label('Custom URL')
                    ->url()
                    ->live()
                    ->placeholder('https://www.example.com'),
            ]);
    }
}
