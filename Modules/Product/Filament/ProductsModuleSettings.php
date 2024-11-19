<?php

namespace Modules\Product\Filament;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class ProductsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'shop/products';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Product Settings')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                Select::make('data-page-id')
                                    ->label('From Source')
                                    ->options([]),
                                Select::make('data-tags')
                                    ->label('Filter Tags')
                                    ->options([]),
                                Radio::make('data-show')
                                    ->label('Display on post')
                                    ->options([
                                        '' => 'Default information from skin',
                                        'custom' => 'Custom information',
                                    ]),
                                Checkbox::make('data-show-thumbnail')
                                    ->label('Thumbnail'),
                                Checkbox::make('data-show-title')
                                    ->label('Title'),
                                TextInput::make('data-title-limit')
                                    ->label('Title Limit')
                                    ->numeric(),
                                Checkbox::make('data-show-description')
                                    ->label('Description'),
                                TextInput::make('data-character-limit')
                                    ->label('Description Limit')

                                    ->numeric(),
                                Checkbox::make('data-show-read-more')
                                    ->label('Read More'),
                                TextInput::make('data-read-more-text')
                                    ->label('Read more text'),
                                Checkbox::make('data-show-date')
                                    ->label('Created At'),
                                TextInput::make('data-limit')
                                    ->label('Post per page')
                                    ->numeric(),
                                Select::make('data-order-by')
                                    ->label('Order by')
                                    ->options([
                                        'position+asc' => 'Position (ASC)',
                                        'position+desc' => 'Position (DESC)',
                                        'created_at+asc' => 'Date (ASC)',
                                        'created_at+desc' => 'Date (DESC)',
                                        'title+asc' => 'Title (ASC)',
                                        'title+desc' => 'Title (DESC)',
                                    ]),
                            ]),

                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ]),
            ]);
    }
}
