<?php

namespace Modules\FacebookPage\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class FacebookPageModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'facebook_page';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Facebook Page')
                    ->schema([
                        TextInput::make('options.fbPage')
                            ->label('Facebook Page URL')
                            ->helperText('The URL of the Facebook page to embed.')
                            ->url()
                            ->live()
                            ->default(fn () => $this->getOption('fbPage', 'https://www.facebook.com/Microweber/')),

                        TextInput::make('options.width')
                            ->label('Width')
                            ->helperText('Widget width in pixels.')
                            ->numeric()
                            ->live()
                            ->default(fn () => $this->getOption('width', '380')),

                        TextInput::make('options.height')
                            ->label('Height')
                            ->helperText('Widget height in pixels.')
                            ->numeric()
                            ->live()
                            ->default(fn () => $this->getOption('height', '300')),

                        Toggle::make('options.friends')
                            ->label('Show Friends Faces')
                            ->helperText('Show the faces of friends who like the page.')
                            ->live()
                            ->default(fn () => filter_var($this->getOption('friends', false), FILTER_VALIDATE_BOOLEAN)),

                        Toggle::make('options.timeline')
                            ->label('Show Timeline')
                            ->helperText('Show the page timeline.')
                            ->live()
                            ->default(fn () => filter_var($this->getOption('timeline', false), FILTER_VALIDATE_BOOLEAN)),
                    ]),
            ]);
    }
}
