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

            ]);
    }
}
