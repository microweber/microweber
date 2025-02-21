<?php

namespace Modules\ImageRollover\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Form;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class ImageRolloverModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'image_rollover';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                MwFileUpload::make('options.default_image')
                    ->label('Default Image')
                    ->helperText('Choose the default image to display.')
                    ->live(),

                MwFileUpload::make('options.rollover_image')
                    ->label('Rollover Image')
                    ->helperText('Choose the image to display on hover.')
                    ->live(),

                TextInput::make('options.size')
                    ->label('Image Size')
                    ->helperText('Enter image size in pixels or "auto"')
                    ->default('350')
                    ->live(),

                TextInput::make('options.text')
                    ->label('Link Title')
                    ->helperText('Create a link below the image')
                    ->live(),

                TextInput::make('options.href-url')
                    ->label('Link URL')
                    ->helperText('Type the URL for the link')
                    ->placeholder('http://')
                    ->live(),
            ]);
    }
}
