<?php

namespace Modules\Menu\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class MenuModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'menu';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('options.menu_name')
                    ->label('Menu Name')
                    ->placeholder('Enter the name of the menu')
                    ->required(),

                MwLinkPicker::make('options.menu_link')
                    ->label('Menu Link')
                    ->helperText('Select or enter the URL for the menu')
                    ->required(),
            ]);
    }
}
