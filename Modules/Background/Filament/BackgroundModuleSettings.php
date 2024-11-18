<?php

namespace Modules\Background\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class BackgroundModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'background';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                MwFileUpload::make('options.background_image')
                    ->label('Background Image URL')
                    ->helperText('Enter the URL of the background image.')
                    ->live(),

                MwFileUpload::make('options.background_video')
                    ->label('Background Video URL')
                    ->helperText('Enter the URL of the background video.')
                    ->live(),

                TextInput::make('options.background_color')
                    ->label('Background Color')
                    ->helperText('Enter the background color in hex format.')
                    ->live(),
            ]);
    }
}
