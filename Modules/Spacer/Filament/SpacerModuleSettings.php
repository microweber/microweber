<?php

namespace Modules\Spacer\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class SpacerModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'spacer';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('options.height')
                    ->label('Height')
                    ->helperText('Enter the height of the spacer (e.g., 50px, 2rem, 5vh).')
                    ->placeholder('50px')

                    ->live(),
            ]);
    }
}
