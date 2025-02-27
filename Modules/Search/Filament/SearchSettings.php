<?php

namespace Modules\Search\Filament;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class SearchSettings extends LiveEditModuleSettings
{
    public string $module = 'search';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        // Content Tab
                        Tabs\Tab::make('Content')
                            ->schema([
                                TextInput::make('options.placeholder')
                                    ->label('Placeholder Text')
                                    ->helperText('Enter the placeholder text for the search field.')
                                    ->live()
                                    ->default('Search...'),

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
                                    ->default(0),

                                Select::make('options.searchPosition')
                                    ->label('Choose the position of the search bar')
                                    ->options(function () {

                                        $options = ['start' => 'Left', 'center' => 'Center' , 'end' => 'Right'];

                                        return $options;
                                    })
                                    ->helperText('Select where to be positioned the search bar.')
                                    ->live()
                                    ->default(1),

                                TextInput::make('options.searchWidth')
                                    ->label('Choose the width of the search bar')
                                    ->helperText('Enter the width of the search bar in px.')
                                    ->live()
                                    ->default('500'),

                                TextInput::make('options.searchHeight')
                                    ->label('Choose the height of the search bar')
                                    ->helperText('Enter the height of the search bar in px.')
                                    ->live()
                                    ->default('100'),

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
                                Section::make('Design settings')->schema(
                                    $this->getTemplatesFormSchema()
                                ),
                            ])
                    ])
            ]);
    }
}
