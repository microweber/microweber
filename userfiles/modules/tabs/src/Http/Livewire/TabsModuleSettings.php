<?php

namespace MicroweberPackages\Modules\Tabs\Http\Livewire;

use App\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class TabsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'tabs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make($this->getOptionFieldName('title'))
                    ->label('Title')
                    ->live(),

            ]);
    }

}
