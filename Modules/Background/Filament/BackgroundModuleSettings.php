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
                MwFileUpload::make('options.data-background-image')
                    ->label('Background Image URL')
                    ->helperText('Enter the URL of the background image.')
                    ->live(),

                MwFileUpload::make('options.data-background-video')
                    ->label('Background Video URL')
                    ->helperText('Enter the URL of the background video.')
                    ->live(),

                TextInput::make('options.data-background-color')
                    ->label('Background Color')
                    ->helperText('Enter the background color in hex format.')
                    ->live(),
                TextInput::make('options.data-background-size')
                    ->label('Background Size')
                    ->helperText('Enter the background size (e.g., cover, contain).')
                    ->live(),
            ]);
    }
}
