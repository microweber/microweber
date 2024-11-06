<?php

namespace Modules\BeforeAfter\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class BeforeAfterModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'before_after';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                MwFileUpload::make('options.before')
                    ->label('Before Image URL')
                    ->helperText('Enter the URL of the before image.')
                    ->live()
                    ->default(asset('modules/before_after/img/white-car.jpg')),

                MwFileUpload::make('options.after')
                    ->label('After Image URL')
                    ->helperText('Enter the URL of the after image.')
                    ->live()
                    ->default(asset('modules/before_after/img/blue-car.jpg')),
            ]);
    }
}
