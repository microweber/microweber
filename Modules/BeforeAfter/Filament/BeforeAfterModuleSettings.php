<?php

namespace Modules\BeforeAfter\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class BeforeAfterModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'beforeafter';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('options.before')
                    ->label('Before Image URL')
                    ->helperText('Enter the URL of the before image.')
                    ->live()
                    ->default(module_url() . 'img/white-car.jpg'),

                TextInput::make('options.after')
                    ->label('After Image URL')
                    ->helperText('Enter the URL of the after image.')
                    ->live()
                    ->default(module_url() . 'img/blue-car.jpg'),
            ]);
    }
}
