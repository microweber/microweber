<?php

namespace Modules\Content\Concerns;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

trait HasContentFilterModuleSettings
{
    public function getContentFilterModuleSettingsSchema()
    {
        return [
            Select::make('options.data-page-id')
                ->label('From Source')
                ->live()
                ->options([]),
            Select::make('options.data-tags')
                ->label('Filter Tags')
                ->live()
                ->options([]),
            Radio::make('options.data-show')
                ->label('Display on post')
                ->live()
                ->options([
                    '' => 'Default information from skin',
                    'custom' => 'Custom information',
                ]),
            Checkbox::make('options.data-show-thumbnail')
                ->label('Thumbnail')->live(),
            Checkbox::make('options.data-show-title')
                ->label('Title')->live(),
            TextInput::make('options.data-title-limit')
                ->label('Title Limit')
                ->numeric()->live(),
            Checkbox::make('options.data-show-description')
                ->label('Description')->live(),
            TextInput::make('data-character-limit')
                ->label('Description Limit')->live()
                ->numeric(),
            Checkbox::make('options.data-show-read-more')
                ->label('Read More')->live(),
            TextInput::make('options.data-read-more-text')
                ->label('Read more text')->live(),
            Checkbox::make('options.data-show-date')
                ->label('Created At')->live(),
            TextInput::make('options.data-limit')
                ->label('Post per page')
                ->numeric()->live(),
            Select::make('options.data-order-by')
                ->label('Order by')
                ->options([
                    'position+asc' => 'Position (ASC)',
                    'position+desc' => 'Position (DESC)',
                    'created_at+asc' => 'Date (ASC)',
                    'created_at+desc' => 'Date (DESC)',
                    'title+asc' => 'Title (ASC)',
                    'title+desc' => 'Title (DESC)',
                ])->live(),
        ];
    }
}
