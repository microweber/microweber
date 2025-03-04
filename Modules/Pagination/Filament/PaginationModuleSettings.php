<?php

namespace Modules\Pagination\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class PaginationModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'pagination';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Pagination settings')
                    ->tabs([
                        Tabs\Tab::make('Main settings')
                            ->schema([
                                TextInput::make('options.paging_param')
                                    ->live()
                                    ->label('Paging Parameter')
                                    ->helperText('The URL parameter name for pagination'),

                                TextInput::make('options.pages_count')
                                    ->live()
                                    ->label('Pages Count')
                                    ->numeric()
                                    ->helperText('Total number of pages'),

                                Toggle::make('options.show_first_last')
                                    ->live()
                                    ->label('Show First/Last')
                                    ->default(true)
                                    ->helperText('Show first and last page links'),

                                TextInput::make('options.limit')
                                    ->live()
                                    ->label('Limit')
                                    ->numeric()
                                    ->default(5)
                                    ->helperText('Number of pagination links to show'),
                            ]),
                        Tabs\Tab::make('Design')
                            ->schema([
                                Section::make('Style Settings')
                                    ->schema([
                                        ColorPicker::make('options.active_color')
                                            ->label('Active Page Color')
                                            ->live(),

                                        ColorPicker::make('options.link_color')
                                            ->label('Link Color')
                                            ->live(),
                                    ]),

                                // Add template settings
                                Section::make('Design settings')->schema(
                                    $this->getTemplatesFormSchema()
                                ),
                            ]),
                    ]),
            ]);
    }
}
