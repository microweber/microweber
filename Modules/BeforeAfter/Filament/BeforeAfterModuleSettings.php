<?php

namespace Modules\BeforeAfter\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class BeforeAfterModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'before_after';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                MwFileUpload::make('options.before')
                    ->label('Before image')
                    ->helperText('The image shown before the slider is dragged.')
                    ->live()
                    ->default(fn () => $this->getOption('before', asset('modules/before_after/img/white-car.jpg'))),

                MwFileUpload::make('options.after')
                    ->label('After image')
                    ->helperText('The image revealed as the slider is dragged.')
                    ->live()
                    ->default(fn () => $this->getOption('after', asset('modules/before_after/img/blue-car.jpg'))),
            ]);
    }
}
