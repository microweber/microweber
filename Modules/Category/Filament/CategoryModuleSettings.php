<?php

namespace Modules\Category\Filament;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Modules\Page\Models\Page;
use Modules\Category\Models\Category;
use MicroweberPackages\Filament\Forms\Components\MwMediaBrowser;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class CategoryModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'category';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('options.data-content-id')
                    ->label('Show Categories From')
                    ->options(Page::query()->whereNotNull('title')->pluck('title', 'id'))
                    ->searchable()
                    ->live()
                    ->placeholder('Select a page'),

                Select::make('options.data-category-id')
                    ->label('Show Only From Category')
                    ->options(Category::query()->whereNotNull('title')->pluck('title', 'id'))
                    ->searchable()
                    ->live()
                    ->placeholder('Select a category'),

                Select::make('options.data-max-depth')
                    ->label('Max Depth')
                    ->options(array_combine(range(0, 10), range(0, 10)))
                    ->live()
                    ->placeholder('None'),

                Toggle::make('options.single_only')
                    ->live()
                    ->label('Show Only Parent Category'),

                Toggle::make('options.show_subcats')
                    ->live()
                    ->label('Show Subcategories'),

                Toggle::make('options.hide_pages')
                    ->live()
                    ->label('Hide Pages'),

                Toggle::make('options.filter_only_in_stock')
                    ->live()
                    ->label('Show Only Products in Stock'),
            ]);
    }
}
