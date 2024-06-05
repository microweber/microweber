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
    public function getOptionGroups(): array
    {
        return [
            static::getOptionGroup()
        ];
    }

    public static function getOptionGroup()
    {
        $optionGroup = 'logo';
        if (request()->get('id')) {
            $optionGroup = request()->get('id');
        }
        return $optionGroup;
    }

    public function getOptionModule(): string
    {
        return 'logo';
    }

    public function form(Form $form): Form
    {
        $optionGroup = self::getOptionGroup();

        return $form
            ->schema([

                Tabs::make('Options')
                    ->tabs([
                        Tabs\Tab::make('Image')
                            ->schema([
                            //    FileUpload::make('options.'.$optionGroup.'.attachment')->live(),
                                TextInput::make('options.'.$optionGroup.'.title')->live()
                            ]),
                        Tabs\Tab::make('Text')
                            ->schema([
                                TextInput::make('options.'.$optionGroup.'.text')
                                    ->label('Logo Text')
                                    ->helperText('This logo text will appear when image not applied')
                                    ->live(),
                                ColorPicker::make('options.'.$optionGroup.'.text_color')
                                    ->live()
                                    ->rgba()
                            ]),
                    ]),

            ]);
    }

}
