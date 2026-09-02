<?php

namespace Modules\Search\Filament;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class SearchSettings extends LiveEditModuleSettings
{
    public string $module = 'search';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Settings')
                    ->schema([
                        // Content Tab
                        Tabs\Tab::make('Content')
                            ->schema([
                                TextInput::make('options.placeholder')
                                    ->label('Placeholder Text')
                                    ->helperText('Enter the placeholder text for the search field.')
                                    ->live()
                                    ->default(fn () => $this->getOption('placeholder', 'Search...')),

                                Select::make('options.data-content-id')
                                    ->label('Search in Page')
                                    ->options(function () {
                                        $pages = get_content('content_type=page&subtype=dynamic&limit=1000');
                                        $options = [0 => 'All pages'];

                                        if (!empty($pages)) {
                                            foreach ($pages as $page) {
                                                $options[$page['id']] = $page['title'];
                                            }
                                        }

                                        return $options;
                                    })
                                    ->helperText('Select which page to search in. Select "All pages" to search in all content.')
                                    ->live()
                                    ->default(fn () => $this->getOption('data-content-id', 0)),

                                Select::make('options.searchPosition')
                                    ->label('Choose the position of the search bar')
                                    ->options(function () {

                                        $options = ['start' => 'Left', 'center' => 'Center' , 'end' => 'Right'];

                                        return $options;
                                    })
                                    ->helperText('Select where to be positioned the search bar.')
                                    ->live()
                                    ->default(fn () => $this->getOption('searchPosition', 'center')),

                                TextInput::make('options.searchWidth')
                                    ->label('Choose the width of the search bar')
                                    ->helperText('Enter the width of the search bar in px.')
                                    ->live()
                                    ->default(fn () => $this->getOption('searchWidth', '300')),

                                TextInput::make('options.searchHeight')
                                    ->label('Choose the height of the search bar')
                                    ->helperText('Enter the height of the search bar in px.')
                                    ->live()
                                    ->default(fn () => $this->getOption('searchHeight', '30')),

                                Toggle::make('options.autocomplete')
                                    ->label('Enable Autocomplete')
                                    ->helperText('Enable autocomplete search functionality.')
                                    ->live()
                                    ->default(false),
                            ]),

                        // Design Tab
                        Tabs\Tab::make('Design')
                            ->schema([
                                // Add template settings
                                Section::make('Design Settings')->schema(
                                    $this->getTemplatesFormSchema()
                                ),
                            ])
                    ])
            ]);
    }
}
