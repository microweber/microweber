<?php

namespace Modules\Category\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\Filament\Forms\Components\MwMediaBrowser;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class CategoryModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'category';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('options.single_only')->label('Show Only Parent Category'),
                TextInput::make('options.show_subcats')->label('Show Subcategories'),
                TextInput::make('options.hide_pages')->label('Hide Pages'),
                TextInput::make('options.filter_only_in_stock')->label('Show Only Products in Stock'),
            ]);
    }
}
