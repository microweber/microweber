<?php

namespace Modules\FacebookLike\Filament;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class FacebookLikeModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'facebook_like';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('options.layout')
                    ->label('Layout')
                    ->options([
                        'standard' => 'Standard',
                        'button_count' => 'Button count',
                        'button' => 'Button',
                        'box_count' => 'Box count',
                    ])
                    ->default(fn () => $this->getOption('layout', 'standard'))
                    ->live(),

                Select::make('options.color')
                    ->label('Color Scheme')
                    ->options([
                        'light' => 'Light',
                        'dark' => 'Dark',
                    ])
                    ->default(fn () => $this->getOption('color', 'light'))
                    ->live(),

                Toggle::make('options.show_faces')
                    ->label('Show Faces')
                    ->default(fn () => filter_var($this->getOption('show_faces', true), FILTER_VALIDATE_BOOLEAN))
                    ->live(),

                TextInput::make('options.url')
                    ->label('Custom URL')
                    ->url()
                    ->live()
                    ->default(fn () => $this->getOption('url', ''))
                    ->placeholder('https://www.example.com'),
            ]);
    }
}
