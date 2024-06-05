<?php

namespace MicroweberPackages\Modules\Logo\Http\Livewire;

use App\Filament\Admin\Pages\Abstract\LiveEditSettingsPageDefault;
use App\Filament\Admin\Pages\Abstract\SettingsPageDefault;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class LogoSettings extends LiveEditSettingsPageDefault
{

    public function getOptionGroups(): array
    {
        return [
            'logo'
        ];
    }

    public function getOptionModule(): string
    {
        return 'logo';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Tabs::make('Options')
                    ->tabs([
                        Tabs\Tab::make('Image')
                            ->schema([
                                FileUpload::make('options.logo.attachment')->live(),
                                TextInput::make('options.logo.title')->live()
                            ]),
                        Tabs\Tab::make('Text')
                            ->schema([
                                TextInput::make('options.logo.text')
                                    ->label('Logo Text')
                                    ->helperText('This logo text will appear when image not applied')
                                    ->live(),
                                ColorPicker::make('options.logo.text_color')
                                    ->live()
                                    ->rgba()
                            ]),
                    ]),

            ]);
    }

}
