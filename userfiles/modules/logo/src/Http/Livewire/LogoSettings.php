<?php

namespace MicroweberPackages\Modules\Logo\Http\Livewire;

use App\Filament\Admin\Pages\Abstract\LiveEditSettingsPageDefault;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class LogoSettings extends LiveEditSettingsPageDefault
{

    public string $optionGroup = '';

    public function getOptionGroups(): array
    {
        return [
            $this->getOptionGroup()
        ];
    }

    public function getOptionGroup()
    {
        $optionGroup = 'logo';
        if (request()->get('id')) {
            $optionGroup = request()->get('id');
            $this->optionGroup = $optionGroup;
        }
        if ($this->optionGroup != '') {
            $optionGroup = $this->optionGroup;
        }
        return $optionGroup;
    }

    public function getOptionModule(): string
    {
        return 'logo';
    }

    public function form(Form $form): Form
    {
        $optionGroup = $this->getOptionGroup();



        return $form
            ->schema([

                Tabs::make('Options')
                    ->tabs([
                        Tabs\Tab::make('Image')
                            ->schema([
                                //    FileUpload::make('options.'.$optionGroup.'.attachment')->live(),
                               // TextInput::make('title')->live()
                            ]),
                        Tabs\Tab::make('Text')
                            ->schema([
                                TextInput::make($this->getFieldName('text', $optionGroup))
                                    ->label('Logo Text')
                                    ->helperText('This logo text will appear when image not applied')
                                    ->live(),
                                ColorPicker::make( $this->getFieldName('text_color', $optionGroup))
                                    ->live()
                                    ->rgba()
                            ]),
                    ]),

            ]);
    }



}
