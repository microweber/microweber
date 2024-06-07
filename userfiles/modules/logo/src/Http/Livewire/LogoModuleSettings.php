<?php

namespace MicroweberPackages\Modules\Logo\Http\Livewire;

use App\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;

class LogoModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'logo';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Tabs::make('Options')
                    ->tabs([
                        Tabs\Tab::make('Image')
                            ->schema([
                                //    FileUpload::make('options.'.$optionGroup.'.attachment')->live(),
                                TextInput::make($this->getOptionFieldName('title'))
                                    ->live()
                            ]),
                        Tabs\Tab::make('Text')
                            ->schema([
                                TextInput::make($this->getOptionFieldName('text'))
                                    ->label('Logo Text')
                                    ->helperText('This logo text will appear when image not applied')
                                    ->live(),
                                ColorPicker::make( $this->getOptionFieldName('text_color'))
                                    ->live()
                                    ->rgba()
                            ]),
                    ]),

            ]);
    }



}
